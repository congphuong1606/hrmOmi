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
use App\Models\Position;
use App\Models\TimeOff;
use App\Models\TimeOn;
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

class EmployeeImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public $skipRows = 2;
    public $takeRows = 24;

    public function getEmployeeExcelDepartments(Request $request)
    {
        $employeeExcelDepartment = EmployeeExcelDepartment::with('department')->get();
        return ApiHelper::responseSuccess('List Employee', ['departments' => $employeeExcelDepartment]);
    }

    public function applyEmployeeExcelDepartment(Request $request)
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
        try {
            DB::beginTransaction();
            foreach ($ids as $value) {
                $employeeExcelDepartment = EmployeeExcelDepartment::find($value['id']);
                if ($employeeExcelDepartment === null) {
                    throw new \ErrorException('Không tìm thấy phòng ban');
                }
                $employeeExcelDepartment->department_id = $value['department_id'];
                $employeeExcelDepartment->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        $employeeExcelDepartment = EmployeeExcelDepartment::with('department')->get();
        return ApiHelper::responseSuccess('List Employee', ['departments' => $employeeExcelDepartment]);
    }


    public function getEmployeeExcelJobStatus(Request $request)
    {
        $employeeExcelJobStatus = EmployeeExcelJobStatus::with('job_status')->get();
        return ApiHelper::responseSuccess('List Employee', ['job_status' => $employeeExcelJobStatus]);
    }

    public function applyEmployeeExcelJobStatus(Request $request)
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
        try {
            DB::beginTransaction();
            foreach ($ids as $value) {
                $employeeExcelJobStatus = EmployeeExcelJobStatus::find($value['id']);
                if ($employeeExcelJobStatus === null) {
                    throw new \ErrorException('Không tìm thấy trạng thái công việc');
                }
                $employeeExcelJobStatus->job_status_id = $value['job_status_id'];
                $employeeExcelJobStatus->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        $employeeExcelJobStatus = EmployeeExcelJobStatus::with('job_status')->get();
        return ApiHelper::responseSuccess('List Employee', ['job_status' => $employeeExcelJobStatus]);
    }

    public function getEmployeeExcelPositions(Request $request)
    {
        $employeeExcelPosition = EmployeeExcelPosition::with('position')->get();
        return ApiHelper::responseSuccess('List Employee', ['positions' => $employeeExcelPosition]);
    }

    public function applyEmployeeExcelPosition(Request $request)
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
        try {
            DB::beginTransaction();
            foreach ($ids as $value) {
                $employeeExcelPosition = EmployeeExcelPosition::find($value['id']);
                if ($employeeExcelPosition === null) {
                    throw new \ErrorException('Không tìm thấy chức danh');
                }
                $employeeExcelPosition->position_id = $value['position_id'];
                $employeeExcelPosition->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        $employeeExcelPosition = EmployeeExcelPosition::with('position')->get();
        return ApiHelper::responseSuccess('List Employee', ['positions' => $employeeExcelPosition]);
    }


    public function getEmployeeExcelFiles(Request $request)
    {
        $employeeExcelFile = EmployeeExcelFile::with('user')->orderBy('created_at', 'DESC')->get();
        return ApiHelper::responseSuccess('List Employee', ['files' => $employeeExcelFile]);
    }

    public function downloadEmployeeExcelFile(Request $request, $id)
    {
        $employeeExcelFile = EmployeeExcelFile::find($id);
        if ($employeeExcelFile === null) {
            return ApiHelper::responseClientFail('file not found!');
        }
        return response()->download(storage_path('app/' . EMPLOYEE_EXCEL_FILES_DIR . ($employeeExcelFile->saved_name === '' ? $employeeExcelFile->name : $employeeExcelFile->saved_name)), $employeeExcelFile->name);
    }

    public function uploadFileEmployee(Request $request)
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
            $employeeExcelFile = new EmployeeExcelFile();
            $employeeExcelFile->name = $file->getClientOriginalName();
            $employeeExcelFile->saved_name = $this->random_string(50) . '.' . $file->getClientOriginalExtension();
            $employeeExcelFile->user_id = \Auth::id();
            $employeeExcelFile->save();
            DB::commit();
            $file->storeAs('personnel_excel/', $employeeExcelFile->saved_name);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail('failed');
        }
        $employeeExcelFiles = EmployeeExcelFile::with('user')->orderBy('created_at', 'DESC')->get();
        return ApiHelper::responseSuccess('Created Employee', ['files' => $employeeExcelFiles]);
    }

    public function parseFile(Request $request, $id)
    {
        $employeeExcelFile = EmployeeExcelFile::find($id);
        if ($employeeExcelFile === null) {
            return ApiHelper::responseClientFail('file not found!');
        }
        $this->parser('storage/app/personnel_excel/', $employeeExcelFile->saved_name === '' ? $employeeExcelFile->name : $employeeExcelFile->saved_name, $employeeExcelFile);
        $employeeExcelFile = EmployeeExcelFile::with(['data' => function($q) {
            $q->with('employee');
        }, 'user'])->where('id', '=', $id)->first();
        return ApiHelper::responseSuccess('Created Employee', ['file' => $employeeExcelFile]);
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
        $employeeExcelFile = EmployeeExcelFile::with('data', 'user')->where('id', '=', $id)->first();
        try {
            DB::beginTransaction();
            foreach ($ids as $value) {
                $employeeExcelData = EmployeeExcelData::where('id', '=', $value)
                    ->where('employee_excel_file_id', '=', $id)
                    ->where('is_accepted', '=', 0)
                    ->whereNull('employee_id')
                    ->where('is_duplicate', '=', 0)
                    ->first();
                if ($employeeExcelData !== null) {
                    $isExistEmployee = $this->isExistEmployee($employeeExcelData->email);
                    if ($isExistEmployee === false) {
                        $user = new User();
                        $user->email = $employeeExcelData->email;
                        $user->name = $employeeExcelData->name;
                        $user->password = DEFAULT_PASSWORD;
                        $user->save();
                        $employee = new Employee();
                        $employee->email = $employeeExcelData->email;
                        $employee->full_name = $employeeExcelData->name;
                        $department = $this->isRowDepartment($employeeExcelData->department);
                        if ($department) {
                            $employee->department_id = $department->department_id;
                        } else {
                            throw new \ErrorException();
                        }
                        $jobStatus = $this->isColumnJobStatus($employeeExcelData->job_status);
                        if ($jobStatus) {
                            $employee->job_status_id = $jobStatus->job_status_id;
                        } else {
                            throw new \ErrorException();
                        }
                        $position = $this->isColumnPosition($employeeExcelData->position);
                        if ($position) {
                            $employee->position_id = $position->position_id;
                        } else {
                            throw new \ErrorException();
                        }
                        $workingStatus = WorkingStatus::query()->where('code', WORKING_STATUS_CODE_WORKING)->first();
                        if ($workingStatus !== null) {
                            $employee->working_status_id = $workingStatus->id;
                        }
                        $employee->birth_day = $employeeExcelData->birthday;
                        $employee->phone_number = $employeeExcelData->phone;
                        $employee->personal_email = $employeeExcelData->personal_email;
                        $employee->skype_account = $employeeExcelData->skype;
                        $employee->facebook_link = $employeeExcelData->facebook;
                        $employee->check_in_date = $employeeExcelData->checkin_date;
                        if ($employeeExcelData->gender === 'Nam') {
                            $employee->gender = EMPLOYEE_GENDER_MALE;
                        }
                        if ($employeeExcelData->gender === 'Nữ') {
                            $employee->gender = EMPLOYEE_GENDER_MALE;
                        }
                        $employee->training_date = $employeeExcelData->training_start_date;
                        $employee->official_date = $employeeExcelData->employee_start_date;
                        $employee->employee_code = $employeeExcelData->fingerprint_id;
                        $employee->identification_number = $employeeExcelData->identification_number;
                        $employee->identification_date = $employeeExcelData->identification_date;
                        $employee->identification_place_of = $employeeExcelData->identification_place;
                        $employee->tax_code = $employeeExcelData->tax_code;
                        $employee->permanent_address = $employeeExcelData->permanent_address;
                        $employee->temporary_address = $employeeExcelData->temporary_address;
                        $employee->bank_number = $employeeExcelData->bank_number;
                        $employee->bank_user_name = $employeeExcelData->bank_user_name;
                        $employee->bank_name = $employeeExcelData->bank_name;
                        $employee->bank_branch = $employeeExcelData->bank_branch;
//                        $employee->distance = $employeeExcelData->distance;
                        $employee->contact_user = $employeeExcelData->contact_user;
                        $employee->japanese_certificate = $employeeExcelData->japanese_certificate;
                        $employee->user_id = $user->id;
                        $employee->save();
                        $employeeExcelData->is_accepted = 1;
                        $employeeExcelData->save();
                    }
                }
            }
            $employeeExcelFile->status = EMPLOYEE_EXCEL_FILE_STATUS_IMPORTED;
            $employeeExcelFile->save();
            TimeOnCalculating::calculatingAccumulatedYear(Carbon::now()->year);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $employeeExcelFile->status = EMPLOYEE_EXCEL_FILE_STATUS_FAILED;
            $employeeExcelFile->save();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        $employeeExcelFile = EmployeeExcelFile::with(['data' => function ($q) {
            $q->with('employee');
        }, 'user'])->where('id', '=', $id)->first();
        return ApiHelper::responseSuccess('Show Employee ', ['file' => $employeeExcelFile]);
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
        $employeeExcelFile = EmployeeExcelFile::find(3);
        $this->parser('storage/app/personnel_excel/', $employeeExcelFile->name, $employeeExcelFile);
    }

    public function parser($dir, $name, $file)
    {
        $departmentName = null;
        $header = [];
        $requiredRow = [2, 3, 4, 6];
        Excel::selectSheetsByIndex(0)->load($dir . $name, function ($reader) use ($departmentName, &$header, $requiredRow, $file) {
            $countRow = 0;
            try {
                DB::beginTransaction();
                EmployeeExcelData::where('employee_excel_file_id', '=', $file->id)
                    ->delete();
                $reader->each(function ($sheet) use (&$departmentName, &$countRow, &$header, $requiredRow, $file) {
                    $countRow++;

                    if ($countRow <= 1) {
                        $header = $sheet->toArray();
                    } else {
                        $isRowDepartment = $this->isRowDepartment($sheet->get(7));
                        if ($isRowDepartment) {
                            $departmentName = $isRowDepartment->name;
                        } else {
                            $isValid = $this->checkRequiredColumn($sheet, $requiredRow);
                            if ($isValid && $departmentName !== null) {
                                $formattedSheet = $this->reformatRow($sheet);
                                $isExistEmployee = $this->isExistEmployee($formattedSheet[9]);
                                $employeeExcelData = new EmployeeExcelData();
                                $employeeExcelData->name = $formattedSheet[2];
                                $employeeExcelData->job_status = $formattedSheet[3];
                                $employeeExcelData->position = $formattedSheet[4];
                                $employeeExcelData->gender = $formattedSheet[5];
                                $employeeExcelData->department = $departmentName;
                                if ($formattedSheet[6] !== null) {
                                    $employeeExcelData->birthday = Carbon::createFromFormat('Y-m-d H:i:s', $formattedSheet[6])->toDateString();
                                }
                                $employeeExcelData->phone = $formattedSheet[7];
                                $employeeExcelData->personal_email = $formattedSheet[8];
                                $employeeExcelData->email = $formattedSheet[9];
                                $employeeExcelData->skype = $formattedSheet[10];
                                $employeeExcelData->facebook = $formattedSheet[11];
                                if ($formattedSheet[12] !== null) {
                                    $employeeExcelData->checkin_date = Carbon::createFromFormat('Y-m-d H:i:s', $formattedSheet[12])->toDateString();
                                }
                                if ($formattedSheet[13] !== null) {
                                    $employeeExcelData->training_start_date = Carbon::createFromFormat('Y-m-d H:i:s', $formattedSheet[13])->toDateString();
                                }
                                if ($formattedSheet[14] !== null) {
                                    $employeeExcelData->employee_start_date = Carbon::createFromFormat('Y-m-d H:i:s', $formattedSheet[14])->toDateString();
                                }
                                $employeeExcelData->fingerprint_id = $formattedSheet[15];
                                $employeeExcelData->identification_number = $formattedSheet[16];
                                if ($formattedSheet[17] !== null) {
                                    $employeeExcelData->identification_date = Carbon::createFromFormat('Y-m-d H:i:s', $formattedSheet[17])->toDateString();
                                }
                                $employeeExcelData->identification_place = $formattedSheet[18];
                                $employeeExcelData->tax_code = $formattedSheet[19];
                                $employeeExcelData->permanent_address = $formattedSheet[20];
                                $employeeExcelData->temporary_address = $formattedSheet[21];
                                $employeeExcelData->bank_number = $formattedSheet[22];
                                $employeeExcelData->bank_user_name = $formattedSheet[23];
                                $employeeExcelData->bank_name = $formattedSheet[24];
                                $employeeExcelData->bank_branch = $formattedSheet[25];
                                $employeeExcelData->contact_user = $formattedSheet[26];
                                //$employeeExcelData->distance = $formattedSheet[27];
                                $employeeExcelData->late_reason_detail = $formattedSheet[27];
                                $employeeExcelData->japanese_certificate = $formattedSheet[28];
                                $employeeExcelData->note = $formattedSheet[29];
                                $employeeExcelData->employee_excel_file_id = $file->id;
                                if ($isExistEmployee) {
                                    $employeeExcelData->employee_id = $isExistEmployee->employee->id;
                                }
                                $employeeExcelData->save();
                                $file->status = EMPLOYEE_EXCEL_FILE_STATUS_IMPORTED;
                                $file->save();
                            }
                        }
                    }
                });

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                dd($e->getMessage());
            }
        });
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


    public function isExistEmployee($content)
    {
        $content = trim(mb_strtolower($content));
        $user = User::with('employee')
            ->where('email', 'LIKE', $content)
            ->first();
        if ($user !== null && $user->employee !== null && $user->employee->deleted_at === null) {
            return $user;
        }
        return false;
    }

    public function checkRequiredColumn($row, array $index = [])
    {
        foreach ($index as $value) {
            $val = $row->get($value);
            if ($val === null) return false;
            if (trim($val) === '') return false;
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
    public function showEmployeeExcelFile($id)
    {
        $employeeExcelFile = EmployeeExcelFile::find($id);
        if ($employeeExcelFile === null) {
            return ApiHelper::responseClientFail('file not found!');
        }
        $this->checkEmployeeExcelFileData($id);
        $employeeExcelFile = EmployeeExcelFile::with(['data' => function($q) {
            $q->with('employee');
        }, 'user'])->where('id', '=', $id)->first();
        return ApiHelper::responseSuccess('Show Employee ', ['file' => $employeeExcelFile]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'employee_code' => 'required',
            'avatar' => 'nullable|image|max:' . MAX_IMAGE_SIZE,
            'department_id' => 'required|cv_exist_department_id',
            'position_id' => 'required|cv_exist_position_id',
            'job_status_id' => 'required|cv_exist_job_status_id',
            'working_status_id' => 'required|cv_exist_working_status_id',
        ], [
            'full_name.required' => 'full_name không được bỏ trống.',
            'employee_code.required' => 'employee_code không được bỏ trống.',
            'email.required' => 'email không được bỏ trống.',
            'email.email' => 'email không đúng',
            'avatar.image' => 'avatar phải là ảnh',
            'avatar.max' => 'avatar quá lớn. Max 5MB',
        ]);

        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validate', [
                'error' => $validator->errors()->toArray()
            ]);
        }
        $skills = $request->input('skills');
        $attach_files = $request->file('attach_files');
        $avatar = $request->file('avatar');
        $jobStatusId = $request->input('job_status_id');
        $jobStatusId = (int)$jobStatusId;;

        try {
            DB::beginTransaction();
            $employee = Employee::find($id);
            $oldJobStatusId = $employee->job_status_id;
            $uniqueFileName = $this->getUniqueImageName();
            $avatarFileName = $uniqueFileName . '.' . RESIZE_IMAGE_FORMAT;
            if ($avatar !== null) {
                $employee->avatar = $avatarFileName;
            }
            $employee->fill($request->all());

            if ($skills !== null) {
                EmployeesSpecializedSkills::where('employee_id', '=', $employee->id)
                    ->delete();
                $skills = explode(',', $skills);
                foreach ($skills as $value) {
                    $employeesSpecializedSkills = new EmployeesSpecializedSkills();
                    $employeesSpecializedSkills->employee_id = $employee->id;
                    $employeesSpecializedSkills->specialized_skill_id = $value;
                    $employeesSpecializedSkills->save();
                }
            }

            if ($attach_files !== null) {
                foreach ($attach_files as $value) {
                    $name = $this->getEmployeeAttachFileName($value);
                    $employeesAttachFiles = new EmployeesAttachFiles();
                    $employeesAttachFiles->employee_id = $employee->id;
                    $employeesAttachFiles->name = $name;
                    $employeesAttachFiles->save();
                    $this->save_employee_attach_file(EMPLOYEE_ATTACH_FILES_DIR, $value, $name);
                }
            }
            $employeeUpdateHistory = new EmployeeUpdateHistory();
            $employeeUpdateHistory->fill($employee->toArray());
            $employeeUpdateHistory->status = UPDATE_STATUS;
            $employeeUpdateHistory->employee_id = $id;
            if ($avatar !== null) {
                $employeeUpdateHistory->avatar = $avatarFileName;
            }
            $employeeUpdateHistory->save();
            if ($oldJobStatusId !== $jobStatusId) {
                $jobStatusUpdateHistory = new JobStatusUpdateHistory();
                $jobStatusUpdateHistory->job_status_id = $jobStatusId;
                $jobStatusUpdateHistory->employee_id = $employee->id;
                $jobStatusUpdateHistory->save();
            };

            if ($avatar !== null) {
                $this->resizeAvatar($avatar, EMPLOYEE_AVATAR_DIR, $uniqueFileName);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());

        }
        return ApiHelper::responseSuccess('Created Employee', ['employee' => $employee]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        //
        try {
            DB::beginTransaction();
            $employee = Employee::find($id);
            $employeeUpdateHistory = new EmployeeUpdateHistory();
            $employeeUpdateHistory->fill($employee->toArray());
            $employeeUpdateHistory->status = DELETE_STATUS;
            $employeeUpdateHistory->employee_id = $id;
            $employee->deleted_at = new \DateTime();
            $employeeUpdateHistory->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Deleted Employee', ['employee_id' => $employee->id]);

    }

    /**
     * Get Employee Department
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getEmployeeDepartment($id)
    {
        //
        $department = Employee::find($id)->department()->get();

        return ApiHelper::responseSuccess('Employee Department', ['department' => $department]);
    }

    /**
     * Get Employee Position
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getEmployeePosition($id)
    {
        //
        $position = Employee::find($id)->position()->get();

        return ApiHelper::responseSuccess('Employee Position', ['position' => $position]);
    }

    /**
     * Get Employee Job Status
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getEmployeeJobStatus($id)
    {
        //
        $jobStatus = Employee::find($id)->jobStatus()->get();

        return ApiHelper::responseSuccess('Employee Job Status', ['job_status' => $jobStatus]);
    }


    public function getExtraInfo()
    {
        $posistion = Position::all();
        $jobStatus = JobStatus::all();
        $department = Department::all();
        $workingStatus = WorkingStatus::all();

        return ApiHelper::responseSuccess('Extra Info',
            ['data' => [
                'job_status' => $jobStatus,
                'position' => $posistion,
                'department' => $department,
                'working_status' => $workingStatus]
            ]);
    }

    /**
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmployeeTimeOff($id, Request $request)
    {
        $action = $request->input('action');
        $page = $request->input('page');
        $limit = $request->input('limit');
        $month = $request->input('month');
        $year = $request->input('year');
        $validator = Validator::make($request->all(), [
            'action' => 'boolean'
        ], [
            'action.boolean' => 'action phải bằng 0 hoặc 1',
        ]);

        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validation', ['error' => $validator->errors()->messages()]);
        }
        $timeOff = TimeOff::where('employee_id', '=', "$id");
        if ($month != null && $year != null) {
            $timeOff->whereMonth('start_datetime', '=', $month)
                ->whereYear('start_datetime', '=', $year);
        }
        if ($action != null) {
            if ($action == 0) {
                $timeOff->where('status', '=', TYPE_IN_LATE)
                    ->orWhere('status', '=', TYPE_LEAVE_OUT)
                    ->orWhere('status', '=', TYPE_LEAVE_EARLY);
            } else {
                $timeOff->where('status', '=', TYPE_ALL_DAY)
                    ->orWhere('status', '=', TYPE_MULTIPLE_DAYS)
                    ->orWhere('status', '=', TYPE_DID_NOT_CHECK_OUT_CHECK_IN);
            }
        }
        $paginatedData = null;;
        $limit = $limit != null ? $limit : DEFAULT_PAGE_LIMIT;
        $page = $page != null ? $page : DEFAULT_DISPLAY_PAGE;

        $paginatedData = $timeOff->orderBy('created_at', 'desc')->paginate($limit, ['*'], 'page', $page);
        $customItems = array();
        foreach ($paginatedData as $item) {
            if ($action != null) {
                if ($action == 0) {
                    array_push($customItems, [
                        'id' => $item->id,
                        'date' => date('d-m-Y', strtotime($item->start_datetime)),
                        'from_time' => date('H:i:s', strtotime($item->start_datetime)),
                        'to_time' => ApiHelper::addTime($item->start_datetime, $item->range, $item->range_unit),
                        'time' => ApiHelper::rangeDisplayValue($item->range),
                        'status' => $item->status,
                        'approved' => $item->approved,
                        'backup_person' => $item->backup_person,
                        'project_manger' => $item->project_manger,
                        'team_leader' => $item->team_leader,
                        'detailed_reason' => $item->detailed_reason,
                        'approved_reason' => $item->approved_reason
                    ]);
                } else {
                    array_push($customItems, [
                        'id' => $item->id,
                        'from_date' => date('d-m-Y', strtotime($item->start_datetime)),
                        'to_date' => ApiHelper::addTime($item->start_datetime, $item->range, $item->range_unit, 'd-m-Y'),
                        'number_of_days' => ApiHelper::rangeDisplayValue($item->range),
                        'status' => $item->status,
                        'approved' => $item->approved,
                        'backup_person' => $item->backup_person,
                        'project_manger' => $item->project_manger,
                        'team_leader' => $item->team_leader,
                        'detailed_reason' => $item->detailed_reason,
                        'approved_reason' => $item->approved_reason
                    ]);
                }
            } else {
                array_push($customItems, $item);
            }
        }
        $data = [
            'time_off' => $customItems,
            'pagination' => [
                'total' => $paginatedData->total(),
                'per_page' => $paginatedData->perPage(),
                'current_page' => $paginatedData->currentPage(),
                'last_page' => $paginatedData->lastPage()
            ]
        ];

        return ApiHelper::responseSuccess('List Time Off', $data);
    }

    /**
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmployeeTimeOn($id, Request $request)
    {
        $page = $request->input('page');
        $limit = $request->input('limit');
        $month = $request->input('month');
        $year = $request->input('year');


        $timeOn = TimeOn::where('employee_id', '=', "$id");
        if ($month != null && $year != null) {
            $timeOn->whereMonth('date', '=', $month)
                ->whereYear('date', '=', $year);
        }
        $paginatedData = null;
        $limit = $limit != null ? $limit : DEFAULT_PAGE_LIMIT;
        $page = $page != null ? $page : DEFAULT_DISPLAY_PAGE;

        $paginatedData = $timeOn->paginate($limit, ['*'], 'page', $page);

        $data = ['time_on' => $paginatedData->items()
            , 'pagination' => ['total' => $paginatedData->total()
                , 'per_page' => $paginatedData->perPage()
                , 'current_page' => $paginatedData->currentPage()
                , 'last_page' => $paginatedData->lastPage()]
        ];

        return ApiHelper::responseSuccess('List Time On', $data);
    }

}
