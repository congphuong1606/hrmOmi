<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseScoreExcelFile;
use App\Models\Training;
use App\Models\User;
use DB;
use Excel;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use JWTAuth;
use Storage;
use Validator;

class CoursesScoreImportController extends Controller
{
    //
    public function uploadScoreFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|integer|min:0',
            'file' => 'required|file',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail(__('messages.validation_0'), ['error' => $validator->errors()->all()]);
        }
        $file = $request->file('file');
        $mimeType = $file->getClientMimeType();
        if (!in_array($mimeType, ApiHelper::$excelMIME)) {
            return ApiHelper::responseClientFail(__('messages.file_invalid'));
        }
        $course_id = $request->input('course_id');
        $course = Course::find($course_id);
        if ($course == null) {
            return ApiHelper::responseClientFail(__('messages.course_exists_0'));
        }
        $user = JWTAuth::toUser($request->token);
        $courseScoreExcelFileId = null;
        try {
            DB::beginTransaction();
            $savingName = $this->getCourseScoreExcelFileName($file);
            $courseScoreExcelFile = new CourseScoreExcelFile();
            $courseScoreExcelFile->course_id = $course_id;
            $courseScoreExcelFile->name = $savingName;
            $courseScoreExcelFile->user_id = $user->id;
            $courseScoreExcelFile->save();
            $courseScoreExcelFileId = $courseScoreExcelFile->id;
            $file->storeAs('training/', $savingName);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Upload successful', ['course_score_excel_file_id' => $courseScoreExcelFileId]);
    }

    public function readScoreFile($id)
    {
        $scoreFile = CourseScoreExcelFile::find($id);
        if ($scoreFile == null) {
            return ApiHelper::responseClientFail(__('messages.course_score_excel_file_exists_0'));
        }
        $name = $scoreFile->name;
        try {
            Storage::get('training/' . $name);
        } catch (FileNotFoundException $e) {
            return ApiHelper:: responseClientFail(__('messages.file_exists_0'));
        }
        $results = array();
        Excel::load('storage/app/training/' . $name, function ($reader) use (&$results) {
            $reader->limitColumns(9);
            $reader->skipRows(1);
            $reader->skipColumns(2);
            $reader->each(function ($row) use (&$results) {
                $tmpRow = array_unique($row->toArray());
                if (is_null($tmpRow[0]) && count($tmpRow) == 1) {
                    return;
                }
                array_push($results, $row);
            });
        });
        return ApiHelper::responseSuccess('File details', ['details' => $results]);
    }

    public function applyScoreFile($id)
    {
        $courseScoreExcelFile = CourseScoreExcelFile::find($id);
        if ($courseScoreExcelFile == null) {
            return ApiHelper::responseClientFail(__('messages.course_score_excel_file_exists_0'));
        }
        $name = $courseScoreExcelFile->name;
        try {
            Storage::get('training/' . $name);
        } catch (FileNotFoundException $e) {
            return ApiHelper:: responseClientFail(__('messages.file_exists_0'));
        }
        $results = array();
        Excel::load('storage/app/training/' . $name, function ($reader) use (&$results) {
            $reader->limitColumns(7);
            $reader->skipRows(2);
            $reader->skipColumns(5);
            $reader->each(function ($row) use (&$results) {
                $tmpRow = array_unique($row->toArray());
                if (is_null($tmpRow[0]) && count($tmpRow) == 1) {
                    return;
                }
                array_push($results, $row);
            });
        });
        try {
            DB::beginTransaction();
            $course_id = $courseScoreExcelFile->course_id;
            $allScoreFile = CourseScoreExcelFile::where('course_id', '=', $course_id)->get();
            foreach ($allScoreFile as $scoreFile) {
                $scoreFile->status = 0;
                $scoreFile->save();
            }
            $training = Training::where('course_id', '=', $course_id)->get();


            if ($training->isEmpty()) {
                DB::rollBack();
                return ApiHelper::responseClientFail(__('messages.file_invalid'));
            }
            $appliedResults = array();
            foreach ($training as $itemTraining) {
                $user_id = $itemTraining->user_id;
                $user = User::find($user_id);
                $email = $user == null ? null : $user->email;
                foreach ($results as $result) {
                    $resultEmail = $result[0];
                    $resultScore = $result[1];
                    if ($email == $resultEmail) {
                        $itemTraining->score = $resultScore;
                        $itemTraining->save();
                        array_push($appliedResults, [$resultEmail, $resultScore]);
                    }
                }
            }
            $courseScoreExcelFile->status = 1;
            $courseScoreExcelFile->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Applied file', ['results' => $appliedResults]);
    }

    public function deleteScoreFile($id)
    {
        $courseScoreExcelFile = CourseScoreExcelFile::find($id);
        if ($courseScoreExcelFile == null) {
            return ApiHelper::responseClientFail(__('messages.course_score_excel_file_exists_0'));
        }
        try {
            DB::beginTransaction();
            $name = $courseScoreExcelFile->name;
            Storage::delete('training/' . $name);
            $courseScoreExcelFile->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Deleted File', ['course_score_excel_file' => $courseScoreExcelFile]);
    }
}
