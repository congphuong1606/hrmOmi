<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeExcelData;
use App\Models\EmployeeExcelDepartment;
use App\Models\EmployeeExcelFile;
use App\Models\EmployeeExcelJobStatus;
use App\Models\EmployeeExcelPosition;
use App\Models\EmployeesAttachFiles;
use App\Models\EmployeesSpecializedSkills;
use App\Models\EmployeeUpdateHistory;
use App\Models\JobStatus;
use App\Models\JobStatusUpdateHistory;
use App\Models\OverTime;
use App\Models\Position;
use App\Models\TimeOff;
use App\Models\TimeOn;
use App\Models\TimeOnExcelData;
use App\Models\TimeOnExcelFile;
use App\Models\TimeOnOfficialHoliday;
use App\Models\TimeOnOverTime;
use App\Models\TimeOnTimeOff;
use App\Models\TimeOnMonth;
use App\Models\User;
use App\Models\WorkingStatus;
use App\Services\TimeOnCalculating;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Validator;

set_time_limit(0);
ini_set('xdebug.max_nesting_level', 1020);

class TimeOnImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public $skipRows = 0;
    public $takeRows = 21;

    public function getTimeOnExcelFiles(Request $request)
    {
        $timeOnExcelFile = TimeOnExcelFile::with('user')->orderBy('created_at', 'DESC')->get();
        return ApiHelper::responseSuccess('List Employee', ['files' => $timeOnExcelFile]);
    }

    public function downloadTimeOnExcelFile(Request $request, $id)
    {
        $timeOnExcelFile = TimeOnExcelFile::find($id);
        if ($timeOnExcelFile === null) {
            return ApiHelper::responseClientFail('file not found!');
        }
        return response()->download(storage_path('app/' . TIME_ON_EXCEL_FILES_DIR . ($timeOnExcelFile->saved_name === '' ? $timeOnExcelFile->name : $timeOnExcelFile->saved_name)), $timeOnExcelFile->name);
    }

    public function uploadFileTimeOn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required',
        ], [

        ]);

        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validate', [
                'error' => $validator->errors()->toArray()
            ]);
        }

        $file = $request->file('file');
        try {
            DB::beginTransaction();
            $timeOnExcelFile = new TimeOnExcelFile();
            $timeOnExcelFile->name = $file->getClientOriginalName();
            $timeOnExcelFile->saved_name = $this->random_string(50) . '.' . $file->getClientOriginalExtension();
            $timeOnExcelFile->user_id = \Auth::id();
            $timeOnExcelFile->save();
            DB::commit();
            $file->storeAs('time_on_excel/', $timeOnExcelFile->saved_name);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail('failed');
        }
        $timeOnExcelFiles = TimeOnExcelFile::with('user')->orderBy('created_at', 'DESC')->get();
        return ApiHelper::responseSuccess('Created Employee', ['files' => $timeOnExcelFiles]);
    }

    public function parseFile(Request $request, $id)
    {
        $timeOnExcelFile = TimeOnExcelFile::find($id);
        if ($timeOnExcelFile === null) {
            return ApiHelper::responseClientFail('file not found!');
        }
        try {
            DB::beginTransaction();
            $this->parser('storage/app/time_on_excel/', $timeOnExcelFile->saved_name === '' ? $timeOnExcelFile->name : $timeOnExcelFile->saved_name, $timeOnExcelFile);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            ApiHelper::responseSuccess('Đã có lỗi xảy ra');
        }

        $timeOnExcelFile = TimeOnExcelFile::with(['data' => function ($q) {
            $q->with(['employee' => function ($q2) {
                $q2->with('department', 'position');
            }]);
        }, 'user'])->where('id', '=', $id)->first();
        return ApiHelper::responseSuccess('Created Employee', ['file' => $timeOnExcelFile]);
    }

    public function truncateImportTables()
    {
        TimeOn::query()->truncate();
        TimeOnMonth::query()->truncate();
        TimeOnOverTime::query()->truncate();
        TimeOnExcelData::query()->truncate();
        TimeOnExcelFile::query()->truncate();
        TimeOnOfficialHoliday::query()->truncate();
        TimeOnTimeOff::query()->truncate();
    }

    public function applyFile(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
        ], [
            'ids.required' => 'ids không được bỏ trống.',
            'ids.array' => 'ids phải là array.',
        ]);

        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validate', [
                'error' => $validator->errors()->toArray()
            ]);
        }
        $ids = $request->input('ids');
        $timeOnExcelFile = TimeOnExcelFile::with('data', 'user')->where('id', '=', $id)->first();
        try {
            DB::beginTransaction();
            foreach ($ids as $value) {
                $timeOnExcelData = TimeOnExcelData::where('id', '=', $value)
                    ->where('time_on_excel_file_id', '=', $id)
                    ->where('is_accepted', '=', 0)
                    ->where('is_duplicate', '=', 0)
                    ->first();
                if ($timeOnExcelData !== null) {
                    $time = Carbon::createFromFormat('Y-m-d', $timeOnExcelData->date);
                    $month = $time->month;
                    $year = $time->year;
                    $isExistTimeOnMonth = TimeOnMonth::query()->where('month', '=', $month)
                        ->where('year', '=', $year)
                        ->where('employee_id', '=', $timeOnExcelData->employee_id)
                        ->first();
                    if ($isExistTimeOnMonth === null) {
                        $isExistTimeOnMonth = new TimeOnMonth();
                        $isExistTimeOnMonth->month = $month;
                        $isExistTimeOnMonth->year = $year;
                        $isExistTimeOnMonth->employee_id = $timeOnExcelData->employee_id;
                        $isExistTimeOnMonth->save();
                    }
                    $daysInMonth = TimeOnCalculating::getDaysInMonth($month, $year);
                    foreach ($daysInMonth as $v) {
                        $isExistTimeOn = TimeOn::query()->whereDate('date', '=', $v['date'])->where('employee_id', '=', $timeOnExcelData->employee_id)->first();
                        if ($isExistTimeOn === null) {
                            $isExistTimeOn = new TimeOn();
                            $isExistTimeOn->status = TIME_ON_STATUS_MISSING_DATA;
                            $isExistTimeOn->is_imported = TIME_ON_UNIMPORTED;
                            $isExistTimeOn->date = $v['date'];
                            $isExistTimeOn->time_on_month_id = $isExistTimeOnMonth->id;
                            $isExistTimeOn->employee_id = $timeOnExcelData->employee_id;
                            $isExistTimeOn->save();
                        }
                    }
                    $timeOn = TimeOn::query()->whereDate('date', '=', $timeOnExcelData->date)->where('employee_id', '=', $timeOnExcelData->employee_id)->first();
                    $timeOn->employee_id = $timeOnExcelData->employee_id;
                    $timeOn->check_in = $timeOnExcelData->check_in;
                    $timeOn->check_out = $timeOnExcelData->check_out;
                    $timeOn->working_time = $timeOnExcelData->working_time;
                    $timeOn->late = $timeOnExcelData->late;
                    $timeOn->leave_early = $timeOnExcelData->leave_early;
                    $timeOn->tc = $timeOnExcelData->tc1;
                    $timeOn->hour = $timeOnExcelData->hour;
                    $timeOn->is_imported = TIME_ON_IMPORTED;
                    if ($timeOn->check_in !== null && $timeOn->check_out !== null) {
                        $timeOn->status = TIME_ON_STATUS_HAS_DATA;
                    }
                    $timeOn->save();
                    $timeOnExcelData->is_accepted = 1;
                    $timeOnExcelData->save();
                }

            }
            $timeOnExcelFile->status = TIME_ON_EXCEL_FILE_STATUS_IMPORTED;
            $timeOnExcelFile->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $timeOnExcelFile->status = TIME_ON_EXCEL_FILE_STATUS_FAILED;
            $timeOnExcelFile->save();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        $timeOnExcelFile = TimeOnExcelFile::with(['data' => function ($q) {
            $q->with(['employee' => function ($q2) {
                $q2->with('department', 'position');
            }]);
        }, 'user'])->where('id', '=', $id)->first();
        return ApiHelper::responseSuccess('Show Employee ', ['file' => $timeOnExcelFile]);
    }

    public function calculateTimeOn()
    {
        TimeOnCalculating::calculateTimeOn();
    }


    public function isExistDate($date, $id)
    {
        $timeOn = TimeOn::where('id', '=', $id)
            ->whereDate('date', '=', $date)
            ->first();
        if ($timeOn !== null) {
            return $timeOn;
        } else {
            return false;
        }
    }

    public function checkEmployeeExcelFileData($id)
    {
        $employeeExcelData = EmployeeExcelData::where('employee_excel_file_id', '=', $id)
            ->get();
        foreach ($employeeExcelData as $key => $value) {
            $isExistEmployee = $this->isExistEmployee($value->email);
            if ($isExistEmployee) {
                $value->employee_id = $isExistEmployee->id;
                $value->save();
            }
            $countDuplicate = 0;
            foreach ($employeeExcelData as $v) {
                if ($v->id !== $value->id && $v->email === $value->email) {
                    $countDuplicate++;
                }
            }
            if ($countDuplicate) {
                $value->is_duplicate = 1;
                $value->save();
            }
        }
    }

    public function parserFile()
    {
        $timeOnExcelFile = TimeOnExcelFile::find(1);
        if ($timeOnExcelFile === null) {
            return ApiHelper::responseClientFail('file not found!');
        }
        $this->parser('storage/app/time_on_excel/', $timeOnExcelFile->name, $timeOnExcelFile);
    }

    public function parser($dir, $name, $file)
    {
        $requiredRow = [0, 4];
        $rowCount = 0;
        config(['excel.import.dates.columns' => [
            4
        ]]);
        TimeOnExcelData::query()->where('time_on_excel_file_id', '=', $file->id)
            ->delete();
        Excel::filter('chunk')->selectSheetsByIndex(0)->load($dir . $name, function ($reader) {
        })->chunk(100, function ($reader) use ($requiredRow, $file, &$rowCount) {
            $reader->each(function ($sheet) use ($requiredRow, $file, &$rowCount) {
                $rowCount++;
                if ($rowCount > 3) {
                    $isValid = $this->checkRequiredColumn($sheet, $requiredRow);
                    if ($isValid) {
                        $formattedSheet = $sheet->toArray();
                        $isExistEmployee = $this->isExistEmployee((int)$formattedSheet[0]);
                        if ($isExistEmployee) {
                            $timeOnExcelData = new TimeOnExcelData();
                            $existTimeOn = TimeOn::query()->whereDate('date', $formattedSheet[4]->toDateString())
                                ->where('employee_id', '=', $isExistEmployee->id)
                                ->where('is_updated', '=', 1)->first();
                            if ($existTimeOn !== null) {
                                $timeOnExcelData->time_on_id = $existTimeOn->id;
                            }
                            $timeOnExcelData->date = $formattedSheet[4]->toDateString();
                            $check_in = $formattedSheet[6] !== null ? Carbon::createFromFormat('H:i', $formattedSheet[6]) : null;
                            $check_out = $formattedSheet[7] !== null ? Carbon::createFromFormat('H:i', $formattedSheet[7]) : null;
                            $timeOnExcelData->check_in = $check_in === null ? null : $check_in->toTimeString();
                            $timeOnExcelData->check_out = $check_out === null ? null : $check_out->toTimeString();
                            $timeOnExcelData->day_off = 0;
                            $timeOnExcelData->working_time = $formattedSheet[8];
                            $timeOnExcelData->late = (int)$formattedSheet[12];
                            $timeOnExcelData->leave_early = (int)$formattedSheet[13];
                            if ($formattedSheet[14] !== null && (float)$formattedSheet[14] > 1) {
                                $timeOnExcelData->tc1 = (float)$formattedSheet[14] - 1;
                            }

                            $timeOnExcelData->tc2 = $formattedSheet[15];
                            $timeOnExcelData->tc3 = $formattedSheet[16];
                            $timeOnExcelData->hour = $formattedSheet[9];
                            $timeOnExcelData->time_on_excel_file_id = $file->id;
                            $timeOnExcelData->employee_id = $isExistEmployee->id;
                            $timeOnExcelData->save();
                        }
                    }
                }

            });

        }, false);
        $file->status = TIME_ON_EXCEL_FILE_STATUS_IMPORTED;
        $file->save();
    }

    public function isRowDepartment($content)
    {
        $content = trim(mb_strtolower($content));
        $employeeExcelDepartment = EmployeeExcelDepartment::where('name', '=', $content)->first();
        if ($employeeExcelDepartment === null) {
            return false;
        } else {
            return $employeeExcelDepartment;
        }
    }

    public function isColumnJobStatus($content)
    {
        $content = trim(mb_strtolower($content));
        $employeeExcelJobStatus = EmployeeExcelJobStatus::where('name', '=', $content)->first();
        if ($employeeExcelJobStatus === null) {
            return false;
        } else {
            return $employeeExcelJobStatus;
        }
    }

    public function isColumnPosition($content)
    {
        $content = trim(mb_strtolower($content));
        $employeeExcelPosition = EmployeeExcelPosition::where('name', '=', $content)->first();
        if ($employeeExcelPosition === null) {
            return false;
        } else {
            return $employeeExcelPosition;
        }
    }


    public function isExistEmployee($employee_code)
    {
        $employee = Employee::where('employee_code', '=', $employee_code)
            ->first();
        if ($employee !== null) {
            return $employee;
        }
        return false;
    }

    public function checkRequiredColumn($row, array $index = [])
    {
        foreach ($index as $value) {
            $val = $row->get($value);
            if ($val === null) return false;
        }
        return true;
    }

    public function reformatRow($row)
    {
        $arr = $row->toArray();
        foreach ($arr as $key => $value) {
            if ($value !== null) {
                $arr[$key] = trim($value) === '' ? null : trim($value);
            }
        }
        return $arr;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTimeOnExcelFile($id)
    {
        $timeOnExcelFile = TimeOnExcelFile::find($id);
        if ($timeOnExcelFile === null) {
            return ApiHelper::responseClientFail('file not found!');
        }
//        $this->checkTimeOnExcelFileData($id);
        $timeOnExcelFile = TimeOnExcelFile::with(['data' => function ($q) {
            $q->with(['employee' => function ($q2) {
                $q2->with('department', 'position');
            }, 'time_on']);
        }, 'user'])->where('id', '=', $id)->first();
        return ApiHelper::responseSuccess('Show Employee ', ['file' => $timeOnExcelFile]);
    }

}
