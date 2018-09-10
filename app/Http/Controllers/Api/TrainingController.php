<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Training;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $training = Training::whereNull('deleted_at')->get();
        return ApiHelper::responseSuccess('List Training', ['training' => $training]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|integer|min:0',
            'user_id' => 'required|integer|min:0',
            'score' => 'required|numeric|min:0'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validation', ['error' => $validator->errors()->all()]);
        }
        $course = Course::whereNull('deleted_at')->where('id', '=', $request->input('course_id'))->first();
        if ($course == null) {
            return ApiHelper::responseClientFail(__('messages.course_exists_0'));
        }
        $user = User::where('id', '=', $request->input('user_id'))->first();
        if ($user == null) {
            return ApiHelper::responseClientFail('The user doesn\'t exists');
        }
        try {
            DB::beginTransaction();
            $training = new Training();
            $training->fill($request->all());
            $training->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Created Training', ['training' => $training]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //
        $training = Training::whereNull('deleted_at')->where('id', '=', $id)->first();
        return ApiHelper::responseSuccess('Training Infomation', ['training' => $training]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //
        try {
            DB::beginTransaction();
            $training = Training::whereNull('deleted_at')->where('id', '=', $id)->first();
            if ($training == null) {
                return ApiHelper::responseClientFail('The training does\'t exists');
            }
            $training->deleted_at = new \DateTime();
            $training->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Deleted Training', ['training' => $training]);
    }

    public function manualMarkScore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'training' => 'required|array',
            'training.*.id' => 'required|integer|min:0',
            'training.*.score' => 'required|numeric|min:-1',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), ['error' => $validator->errors()->all()]);
        }
        $training = $request->input('training');
        try {
            DB::beginTransaction();
            $updatedTraining = array();
            foreach ($training as $itemTraining) {
                $currentTraining = Training::find($itemTraining['id']);
                if ($currentTraining) {
                    $currentTraining->score = $itemTraining['score'] < 0 ? null : $itemTraining['score'];
                    $currentTraining->save();
                    array_push($updatedTraining, $currentTraining);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return ApiHelper::responseSuccess('Marked score', ['training' => $updatedTraining]);
    }

    public function getTrainingReport($course_id)
    {
        $course = Course::with('sessions', 'otherCategory')
            ->whereHas('otherCategory')
            ->whereHas('sessions')
            ->find($course_id);
        if ($course == null) {
            return ApiHelper::responseClientFail(__('messages.course_exists_0'));
        }
        $training = Training::with('user', 'user.employee.department', 'user.employee.position')
            ->where('course_id', '=', $course_id)
            ->whereHas('user', function ($query) {
                $query->whereHas('employee', function ($query) {
//                    $query->whereHas('department');
//                    $query->whereHas('position');
                });
            })->get();
//        return $training;
        $trainingData = array();
        $order = 0;
        foreach ($training as $item) {
            $order++;
            $employee = $item->user->employee;
            $name = $employee->full_name == null ? __('messages.null') : $employee->full_name;
            $department = $employee->department == null ? __('messages.null') : $employee->department->name;
            $position = $employee->position == null ? __('messages.null') : $employee->position->name;
            array_push($trainingData, [
                'order' => $order,
                'name' => $name,
                'position' => $position,
                'department' => $department,
                'score' => $item->score,
            ]);
        }
        $trainers = array();
        $sessions = $course->sessions;

        foreach ($sessions as $session) {
            array_push($trainers, ApiHelper::getNameFromEmail($session->trainer));
        }
        $trainers = array_unique($trainers);
        $data = [
            'training_date' => date('d-m-Y', strtotime($sessions[0]->start_datetime)),
            'course_name' => $course->otherCategory->name,
            'trainers' => $trainers,
            'training' => $trainingData,
        ];
        $pdf = PDF::loadView('report.report_course_training', $data);
        return $pdf->stream();
    }
}
