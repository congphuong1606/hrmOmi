<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeLateReason;
use App\Models\EmployeesAttachFiles;
use App\Models\EmployeesSpecializedSkills;
use App\Models\EmployeeUpdateHistory;
use App\Models\JobStatus;
use App\Models\JobStatusUpdateHistory;
use App\Models\Position;
use App\Models\Setting;
use App\Models\TimeOff;
use App\Models\TimeOn;
use App\Models\TimeOnAccumulatedYear;
use App\Models\TimeOnAdditionDayOff;
use App\Models\TimeOnMonth;
use App\Models\User;
use App\Models\WorkingStatus;
use App\Services\TimeOnCalculating;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public $skipRows = 2;
    public $takeRows = 24;

    public function index(Request $request)
    {

        $limit = $request->input('limit');
        $page = $request->input('page');
        $name = $request->input('name');
        $employee_code = $request->input('id');
        $department = $request->input('department');
        $position = $request->input('position');
        $jobStatus = $request->input('job_status');
        $workingStatus = $request->input('working_status');

        $employee = Employee::query()->with('department', 'position', 'jobStatus', 'user', 'workingStatus')
            ->whereNull('deleted_at');
        if ($name !== null) {
            $employee->where('full_name', 'like', "%$name%");
        }
        if ($employee_code !== null) {
            $employee->where('employee_code', '=', $employee_code);
        }

        if ($department !== null && $department !== '0') {
            $employee->whereHas('department', function ($q) use ($department) {
                $q->where('id', '=', "$department");
            });
        }

        if ($position !== null && $position !== '0') {
            $employee->whereHas('position', function ($q) use ($position) {
                $q->where('id', '=', "$position");
            });
        }

        if ($jobStatus !== null && $jobStatus !== '0') {
            $employee->whereHas('jobStatus', function ($q) use ($jobStatus) {
                $q->where('id', '=', "$jobStatus");
            });
        }

        if ($workingStatus !== null && $workingStatus !== '0') {
            $employee->whereHas('workingStatus', function ($q) use ($workingStatus) {
                $q->where('id', '=', "$workingStatus");
            });
        }
        $employee->orderBy('created_at', 'DESC');
        $employees = array();
        $paginated_data = null;
        $data = null;
        if ($limit) {

            if ($page) {
                $paginated_data = $employee->paginate($limit, ['*'], 'page', $page);

            } else {
                $paginated_data = $employee->paginate($limit, ['*'], 'page', DEFAULT_DISPLAY_PAGE);
            }

            foreach ($paginated_data as $e) {
                $job_status_history = JobStatusUpdateHistory::with('jobStatus')->where('employee_id', '=', $e->id)->get();

                $e->job_status_history = $job_status_history;
                array_push($employees, $e);
            }
            $data = ['employees' => $employees
                , 'pagination' => ['total' => $paginated_data->total()
                    , 'per_page' => $paginated_data->perPage()
                    , 'current_page' => $paginated_data->currentPage()
                    , 'last_page' => $paginated_data->lastPage()]
            ];
        } else {
            if ($page) {
                $paginated_data = $employee->paginate(DEFAULT_PAGE_LIMIT, ['*'], 'page', $page);
            } else {
                $paginated_data = $employee->paginate(DEFAULT_PAGE_LIMIT, ['*'], 'page', DEFAULT_DISPLAY_PAGE);
            }

            foreach ($paginated_data as $e) {
                $job_status_history = JobStatusUpdateHistory::with('jobStatus')->where('employee_id', '=', $e->id)->get();
                $e->job_status_history = $job_status_history;
                array_push($employees, $e);
            }
            $data = ['employees' => $employees
                , 'pagination' => ['total' => $paginated_data->total()
                    , 'per_page' => $paginated_data->perPage()
                    , 'current_page' => $paginated_data->currentPage()
                    , 'last_page' => $paginated_data->lastPage()]
            ];
        }
        return ApiHelper::responseSuccess('List Employee', $data);
    }

    public function validateEmailCreate(Request $request)
    {
        $email = $request->input('email');
        if ($email === null) {
            return ApiHelper::responseSuccess('Expected Param', [
                'result' => 'invalid'
            ]);
        }
        $existEmail = Employee::query()->where('email', '=', $email)->first();
        if ($existEmail === null) {
            return ApiHelper::responseSuccess('OK', [
                'result' => 'valid'
            ]);
        }
        return ApiHelper::responseSuccess('email đã tồn tại', [
            'result' => 'invalid'
        ]);
    }

    public function validateEmailUpdate(Request $request)
    {
        $email = $request->input('email');
        $employee_id = $request->input('employee_id');
        if ($email === null || $employee_id === null) {
            return ApiHelper::responseSuccess('Expected Param', [
                'result' => 'invalid'
            ]);
        }
        $existEmail = Employee::query()->where('email', '=', $email)
            ->where('id', '<>', $employee_id)
            ->first();
        if ($existEmail === null) {
            return ApiHelper::responseSuccess('OK', [
                'result' => 'valid'
            ]);
        }
        return ApiHelper::responseSuccess('email đã tồn tại', [
            'result' => 'invalid'
        ]);
    }

    public function validateEmployeeCodeCreate(Request $request)
    {
        $employee_code = $request->input('employee_code');
        if ($employee_code === null) {
            return ApiHelper::responseSuccess('Expected Param', [
                'result' => 'invalid'
            ]);
        }
        $existEmployeeCode = Employee::query()->where('employee_code', '=', $employee_code)->first();
        if ($existEmployeeCode === null) {
            return ApiHelper::responseSuccess('OK', [
                'result' => 'valid'
            ]);
        }
        return ApiHelper::responseSuccess('mã nhân viên đã tồn tại', [
            'result' => 'invalid'
        ]);
    }

    public function validateEmployeeCodeUpdate(Request $request)
    {
        $employee_code = $request->input('employee_code');
        $employee_id = $request->input('employee_id');
        if ($employee_code === null || $employee_id === null) {
            return ApiHelper::responseSuccess('Expected Param', [
                'result' => 'invalid'
            ]);
        }
        $existEmployeeCode = Employee::query()->where('employee_code', '=', $employee_code)
            ->where('id', '<>', $employee_id)
            ->first();
        if ($existEmployeeCode === null) {
            return ApiHelper::responseSuccess('OK', [
                'result' => 'valid'
            ]);
        }
        return ApiHelper::responseSuccess('mã nhân viên đã tồn tại', [
            'result' => 'invalid'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $url = '/danh-sach-nhan-su/them-nhan-su';
        $checkPermission = false;
        if ($this->isAdmin()) {
            $checkPermission = true;
        } else {
            $user = User::query()->whereHas('employee', function ($query) {
            })->find(\Auth::id());
            if ($user == null) {
                return ApiHelper::responseClientFail(__('messages.user_exists_0'));
            }
            $roles = $user->roles()->get();
            foreach ($roles as $role) {
                if (mb_strtolower($role->name) === 'admin') {
                    $checkPermission = true;
                    break;
                }
                $screens = $role->screens()->whereNull('deleted_at')->get();
                foreach ($screens as $screen) {
                    if ($screen && $screen->url === $url) {
                        $checkPermission = true;
                    }
                }
            }
        }

        if (!$checkPermission) {
            return ApiHelper::responseClientFail('Bạn không có quyền truy cập');
        }
        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'employee_code' => 'required|cv_unique_employee_code',
            //Minimum eight characters, at least one uppercase letter, one lowercase letter and one number:
            'password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/',
            'email' => 'required|email|cv_unique_employee_email',
            'avatar' => 'nullable|image|max:' . MAX_IMAGE_SIZE,
            'department_id' => 'required|cv_exist_department_id',
            'position_id' => 'required|cv_exist_position_id',
            'job_status_id' => 'required|cv_exist_job_status_id',
            'working_status_id' => 'required|cv_exist_working_status_id',
            'skills' => 'nullable',
            'attach_files' => 'nullable',
            'attach_files.*' => 'file',
        ], [
            'full_name.required' => 'tên không được bỏ trống.',
            'employee_code.required' => 'mã nhân viên không được bỏ trống.',
            'employee_code.cv_unique_employee_code' => 'mã nhân viên đã tồn tại.',
            'password.required' => 'mật khẩu không được bỏ trống.',
            'password.regex' => 'mật khẩu ít nhất 8 chữ và có 1 chữ thường, 1 chữ hoa, 1 số',
            'email.required' => 'email không được bỏ trống.',
            'email.email' => 'email không đúng',
            'email.cv_unique_employee_email' => 'email đã tồn tại',
            'avatar.image' => 'avatar phải là ảnh',
            'avatar.max' => 'avatar quá lớn. Max 5MB',
            'department_id.required' => 'phòng ban không được bỏ trống',
            'department_id.cv_exist_department_id' => 'phòng ban không tồn tại',
            'position_id.required' => 'chức danh không được bỏ trống',
            'position_id.cv_exist_position_id' => 'chức danh không tồn tại',
            'job_status_id.required' => 'trạng thái công việc không được bỏ trống',
            'job_status_id.cv_exist_job_status_id' => 'trạng thái công việc không tồn tại',
            'working_status_id.required' => 'tình trạng việc làm không được bỏ trống',
            'working_status_id.cv_exist_working_status_id' => 'tình trạng việc làm không tồn tại',
            'attach_files.*' => 'File đính kèm không đúng định dạng',
        ]);

        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validate', [
                'error' => $validator->errors()->toArray()
            ]);
        }

        $email = $request->input('email');
        $full_name = $request->input('full_name');
        $password = $request->input('password');
        $late_reason_id = $request->input('late_reason_id');
        if ($late_reason_id === null) {
            $late_reason_id = 0;
        } else {
            $late_reason_id = (int)$late_reason_id;
        }

        $job_status_id = $request->input('job_status_id');
        $avatar = $request->file('avatar');
        $skills = $request->input('skills');
        $attach_files = $request->file('attach_files');
        $late_reasons = $request->input('late_reasons');

        try {
            DB::beginTransaction();
            $user = new User();
            $user->name = $full_name;
            $user->email = $email;
            $user->password = $password;
            $user->save();

            $employee = new Employee();
            $employee->fill($request->all());
            if ($late_reason_id) {
                $employee->late_reason_id = $late_reason_id;
            } else {
                $employee->late_reason_id = null;
            }

            $employee->user_id = $user->id;
            $uniqueFileName = $this->getUniqueImageName();
            $avatarFileName = $uniqueFileName . '.' . RESIZE_IMAGE_FORMAT;
            if ($avatar !== null) {
                $employee->avatar = $avatarFileName;
            }
            $employee->save();
            if ($late_reasons !== null) {
                foreach ($late_reasons as $value) {
                    if ($value['late_reason_id'] !== null && (int)$value['late_reason_id'] !== 0 && $value['start_date'] !== null) {
                        $employeeLateReason = new EmployeeLateReason();
                        $employeeLateReason->employee_id = $employee->id;
                        $employeeLateReason->late_reason_id = $value['late_reason_id'];
                        $employeeLateReason->start_date = $value['start_date'];
                        $employeeLateReason->end_date = $value['end_date'];
                        $employeeLateReason->save();
                    }
                }
            }

            if ($skills !== null) {
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
                    $employeesAttachFiles->name = $value->getClientOriginalName();
                    $employeesAttachFiles->saved_name  = $this->random_string(50) . '.' . $value->getClientOriginalExtension();
                    $employeesAttachFiles->save();
                    $this->save_employee_attach_file(EMPLOYEE_ATTACH_FILES_DIR, $value, $employeesAttachFiles->saved_name);
                }
            }
            $jobStatusUpdateHistory = new JobStatusUpdateHistory();
            $jobStatusUpdateHistory->job_status_id = $job_status_id;
            $jobStatusUpdateHistory->employee_id = $employee->id;
            $jobStatusUpdateHistory->save();

            if ($avatar !== null) {
                $this->resizeAvatar($avatar, EMPLOYEE_AVATAR_DIR, $uniqueFileName);
            }
            TimeOnCalculating::calculatingAccumulatedYear(Carbon::now()->year, [$employee->id]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getFile());
        }
        return ApiHelper::responseSuccess('Created Employee', ['employee' => $employee]);
    }

    public function downloadAttachFile(Request $request)
    {
        $file_name = $request->input('file');
        $file = EmployeesAttachFiles::query()->where('saved_name', $file_name)->orWhere('name', $file_name)->first();
        if ($file === null) {
            abort(404);
        }
        if ($file->saved_name === '') {
            $path = storage_path("app/" . EMPLOYEE_ATTACH_FILES_DIR . $file->name);
        } else {
            $path = storage_path("app/" . EMPLOYEE_ATTACH_FILES_DIR . $file->saved_name);
        }
        return response()->download($path, $file->name);
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
        $employee = Employee::with(['department', 'position', 'jobStatus', 'user', 'workingStatus', 'specialized_skills', 'attach_files', 'late_reasons',
            'employee_late_reasons' => function ($q) {
                $q->with('late_reason');
            }
        ])
            ->where('id', '=', $id)
            ->first();
        if ($employee === null) {
            return ApiHelper::responseClientFail('Người dùng không tồn tại');
        }
        $job_status_history = JobStatusUpdateHistory::with('jobStatus')->where('employee_id', '=', $id)->get();
        $token = \JWTAuth::getToken();
        foreach ($employee->attach_files as $key => $value) {
            if ($value->name !== null) {
                $value->url = DOMAIN . 'api/employees/files/download?file=' . $value->saved_name . '&token=' . $token;
            }
        }
        if ($employee) {
            $employee->job_status_history = $job_status_history;
        }
        return ApiHelper::responseSuccess('Show Employee ', ['employee' => $employee]);
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
        $url = '/danh-sach-nhan-su/sua-thong-tin-nhan-su';
        $checkPermission = false;
        if ($this->isAdmin()) {
            $checkPermission = true;
        } else {
            $user = User::query()->whereHas('employee', function ($query) {
            })->find(\Auth::id());
            if ($user == null) {
                return ApiHelper::responseClientFail(__('messages.user_exists_0'));
            }
            $roles = $user->roles()->get();
            foreach ($roles as $role) {
                if (mb_strtolower($role->name) === 'admin') {
                    $checkPermission = true;
                    break;
                }
                $screens = $role->screens()->whereNull('deleted_at')->get();
                foreach ($screens as $screen) {
                    if ($screen && $screen->url === $url) {
                        $checkPermission = true;
                    }
                }
            }
        }

        $rules = [
            'full_name' => 'required',
            'employee_code' => 'required',
            'department_id' => 'cv_exist_department_id',
            'position_id' => 'cv_exist_position_id',
            'job_status_id' => 'cv_exist_job_status_id',
            'working_status_id' => 'cv_exist_working_status_id',
            'avatar' => 'nullable|image|max:' . MAX_IMAGE_SIZE,
        ];
        $messages = [
            'full_name.required' => 'tên không được bỏ trống.',
            'employee_code.required' => 'mã nhân viên không được bỏ trống.',
            'department_id.cv_exist_department_id' => 'phòng ban không tồn tại.',
            'position_id.cv_exist_position_id' => 'chức danh không tồn tại.',
            'job_status_id.cv_exist_job_status_id' => 'trạng thái công việc không tồn tại.',
            'working_status_id.cv_exist_working_status_id' => 'tình trạng làm việc không tồn tại.',
            'avatar.image' => 'avatar phải là ảnh',
            'avatar.max' => 'avatar quá lớn. Max 5MB',
        ];
        $isAdmin = \Auth::user()->roles()->where('name', 'like', 'admin')->first() !== null;
        if ($isAdmin) {
            $rules['avatar'] = 'nullable|image|max:' . MAX_IMAGE_SIZE;
            $messages['email.required'] = 'email không được bỏ trống.';
            $messages['email.email'] = 'email không đúng';
        }
        $validator = Validator::make($request->all(), $rules, $messages);

        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validate', [
                'error' => $validator->errors()->toArray()
            ]);
        }
        $skills = $request->input('skills');
        $attach_files = $request->file('attach_files');
        $avatar = $request->file('avatar');
        $jobStatusId = $request->input('job_status_id');
        if ($jobStatusId !== null) {
            $jobStatusId = (int)$jobStatusId;
        }
        $current_attach_files = $request->input('current_attach_files');
        $late_reason_id = $request->input('late_reason_id');
        if ($late_reason_id === null) {
            $late_reason_id = 0;
        } else {
            $late_reason_id = (int)$late_reason_id;
        }
        $late_reasons = $request->input('late_reasons');

        try {
            DB::beginTransaction();
            $employee = Employee::find($id);
            $oldEmail = $employee->email;
            $oldJobStatusId = $employee->job_status_id;
            $uniqueFileName = $this->getUniqueImageName();
            $avatarFileName = $uniqueFileName . '.' . RESIZE_IMAGE_FORMAT;
            if ($avatar !== null) {
                $employee->avatar = $avatarFileName;
            }
            $employee->fill($request->all());
            if (!$isAdmin) {
                $employee->email = $oldEmail;
            }
            if ($late_reason_id) {
                $employee->late_reason_id = $late_reason_id;
            } else {
                $employee->late_reason_id = null;
            }

            if ($checkPermission) {
                EmployeeLateReason::query()->where('employee_id', $id)
                    ->delete();
                if ($late_reasons !== null) {
                    foreach ($late_reasons as $value) {
                        if ($value['late_reason_id'] !== null && (int)$value['late_reason_id'] !== 0 && $value['start_date'] !== null) {
                            $employeeLateReason = new EmployeeLateReason();
                            $employeeLateReason->employee_id = $id;
                            $employeeLateReason->late_reason_id = $value['late_reason_id'];
                            $employeeLateReason->start_date = $value['start_date'];
                            $employeeLateReason->end_date = $value['end_date'];
                            $employeeLateReason->save();
                        }
                    }
                }
                if ($current_attach_files !== null) {
                    $delete_file_ids = [];
                    foreach ($current_attach_files as $value) {
                        if (isset($value['deleted']) && $value['deleted'] === 'true') {
                            array_push($delete_file_ids, $value['id']);
                        }
                    }
                    if (sizeof($delete_file_ids)) {
                        EmployeesAttachFiles::query()->whereIn('id', $delete_file_ids)->delete();
                    }
                }
                if ($attach_files !== null) {
                    foreach ($attach_files as $value) {
                        $employeesAttachFiles = new EmployeesAttachFiles();
                        $employeesAttachFiles->employee_id = $employee->id;
                        $employeesAttachFiles->name = $value->getClientOriginalName();
                        $employeesAttachFiles->saved_name  = $this->random_string(50) . '.' . $value->getClientOriginalExtension();
                        $employeesAttachFiles->save();
                        $this->save_employee_attach_file(EMPLOYEE_ATTACH_FILES_DIR, $value, $employeesAttachFiles->saved_name);
                    }
                }
                $employee->save();
                TimeOnCalculating::calculatingAccumulatedYear(Carbon::now()->year, [$employee->id]);
            }

            if ($skills !== null) {
                EmployeesSpecializedSkills::query()->where('employee_id', '=', $employee->id)
                    ->delete();
                $skills = explode(',', $skills);
                foreach ($skills as $value) {
                    $employeesSpecializedSkills = new EmployeesSpecializedSkills();
                    $employeesSpecializedSkills->employee_id = $employee->id;
                    $employeesSpecializedSkills->specialized_skill_id = $value;
                    $employeesSpecializedSkills->save();
                }
            }

            if (!$checkPermission) {
                $employeeUpdateHistory = new EmployeeUpdateHistory();
                $employeeUpdateHistory->fill($employee->toArray());
                $employeeUpdateHistory->status = UPDATE_STATUS;
                $employeeUpdateHistory->employee_id = $id;
                if ($avatar !== null) {
                    $employeeUpdateHistory->avatar = $avatarFileName;
                } else {
                    $employeeUpdateHistory->avatar = $employee->getOriginal('avatar');
                }
                $employeeUpdateHistory->save();
            }
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
            $employee->deleted_at = Carbon::now()->toDateTimeString();
            $employeeUpdateHistory->save();
            $employee->save();
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmployeeTimeOff(Request $request)
    {
        $user = \JWTAuth::toUser($request->token);
        $employee = Employee::whereNull('deleted_at')->where('user_id', '=', $user->id)->first();
        $setting = Setting::first();
        $response = $this->getEmployeeTimeOffValidation($request, $setting, $employee, $user);
        if ($response != null) {
            return $response;
        }

        $threshold = $setting->time_off_registration_threshold;
        $timeOff = TimeOff::whereNull('deleted_at')
            ->whereHas('employee', function ($query) use ($employee) {
                $query->where('id', '=', $employee->id);
            });

        $action = $request->input('action');
        $page = $request->input('page');
        $limit = $request->input('limit');
        $month = $request->input('month');
        $year = $request->input('year');
        if ($month != null && $year != null) {
            $timeOff->whereMonth('start_datetime', '=', $month)
                ->whereYear('start_datetime', '=', $year);
        }
        if ($action != null) {
            if ($action == 0) {
                $timeOff->where(function ($query) {
                    $query->where('status', '=', TYPE_IN_LATE)
                        ->orWhere('status', '=', TYPE_LEAVE_OUT)
                        ->orWhere('status', '=', TYPE_LEAVE_EARLY);
                });
            } else {
                $timeOff->where(function ($query) {
                    $query->where('status', '=', TYPE_ALL_DAY)
                        ->orWhere('status', '=', TYPE_MULTIPLE_DAYS)
                        ->orWhere('status', '=', TYPE_DID_NOT_CHECK_OUT_CHECK_IN);
                });
            }
        }
        $paginatedData = null;
        $limit = $limit != null ? $limit : DEFAULT_PAGE_LIMIT;
        $page = $page != null ? $page : DEFAULT_DISPLAY_PAGE;
        $paginatedData = $timeOff->orderBy('created_at', 'desc')->paginate($limit, ['*'], 'page', $page);
        $customItems = array();
        foreach ($paginatedData as $item) {
            \Log::info('item: ' . $item);
            $start = $item->start_datetime;
            $end = $item->end_datetime;
            $status = $item->status;
            $start_time = strtotime($start);
            $end_time = strtotime($end);
            $now = strtotime((new \DateTime())->format('Y-m-d H:i:s'));
            $day = ($now - $start_time) / 60 / 60 / 24;
            $approved = $day > $threshold && $item->approved == PENDING ? REFUSED : $item->approved;
            $approvedReason = $day > $threshold && $item->approved == PENDING ? EXPIRE_MASSAGE : $item->approved_reason;
            $updated_at = $item->updated_at == null ? null : $item->updated_at;
            if ($action != null) {
                if ($action == 0) {
                    array_push($customItems, [
                        'id' => $item->id,
                        'date' => date('d-m-Y', $start_time),
                        'from_time' => date('H:i', $start_time),
                        'to_time' => date('H:i', $end_time),
                        'time' => ApiHelper::timeRangeComputation($start, $end, $status, $item->employee_id),
                        'status' => $status,
                        'approved' => $approved,
                        'backup_person' => $item->backup_person,
                        'project_manger' => $item->project_manger,
                        'team_leader' => $item->team_leader,
                        'reason' => $item->reason,
                        'detailed_reason' => $item->detailed_reason,
                        'approved_reason' => $approvedReason,
                        'forget_type' => $item->forget_type,
                        'updated_at' => date('d-m-Y H:i', strtotime($updated_at)),
                    ]);
                } else {
                    array_push($customItems, [
                        'id' => $item->id,
                        'from_date' => date('d-m-Y H:i', $start_time),
                        'to_date' => date('d-m-Y H:i', $end_time),
                        'number_of_days' => ApiHelper::timeRangeComputation($start, $end, $status, $item->employee_id),
                        'status' => $status,
                        'approved' => $approved,
                        'backup_person' => $item->backup_person,
                        'project_manger' => $item->project_manger,
                        'team_leader' => $item->team_leader,
                        'reason' => $item->reason,
                        'detailed_reason' => $item->detailed_reason,
                        'approved_reason' => $approvedReason,
                        'forget_type' => $item->forget_type,
                        'updated_at' => date('d-m-Y H:i', strtotime($updated_at)),
                    ]);
                }
            } else {
                $item->range = ApiHelper::timeRangeComputation($start, $end, $status, $item->employee_id);
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

    public function getEmployeeTimeOffValidation(Request $request, $setting, $employee, $user)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'boolean',
            'page' => 'integer|min:1',
            'limit' => 'integer|min:1',
            'month' => 'integer|min:1|max:12',
            '$year' => 'integer|min:1971',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validation', ['error' => $validator->errors()->messages()]);
        }
        if ($user == null) {
            return ApiHelper::responseClientFail(__('messages.user_exists_0'));
        }
        if ($setting == null) {
            return ApiHelper::responseClientFail(__('messages.setting_exists_0'));
        }
        if ($employee == null) {
            return ApiHelper::responseClientFail(__('messages.employee_exists_0'));
        }
        return null;
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


        $timeOn = TimeOn::where('deleted_at', '=', null)
            ->where('employee_id', '=', "$id");
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

    public function getBirthdayInMonth(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'integer|min:0|nullable',
            'limit' => 'integer|min:0|nullable'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), ['error' => $validator->errors()->all()]);
        }
        $page = $request->input('page');
        $limit = $request->input('limit');
        $page = $page == null ? DEFAULT_DISPLAY_PAGE : $page;
        $limit = $limit == null ? DEFAULT_PAGE_LIMIT : $limit;

        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentDay = $now->day;
        $paginatedData = Employee::with('department', 'user')
            ->whereNull('deleted_at')
            ->whereHas('user')->whereHas('department')
            ->whereMonth('birth_day', '=', $currentMonth)
            ->whereDay('birth_day', '>=', $currentDay)
            ->orderByRaw('DAY(birth_day)')
            ->paginate($limit, ['*'], 'page', $page);
        $employees = array();
        foreach ($paginatedData as $employee) {
            array_push($employees, [
                'id' => $employee->id,
                'full_name' => $employee->full_name,
                'birth_day' => $employee->birth_day,
                'department' => $employee->department->name
            ]);
        }
        $data = [
            'employees' => $employees,
            'pagination' => [
                'total' => $paginatedData->total(),
                'per_page' => $paginatedData->perPage(),
                'current_page' => $paginatedData->currentPage(),
                'last_page' => $paginatedData->lastPage()
            ]
        ];
        return ApiHelper::responseSuccess('List Employee', $data);
    }

    public function getTimeOffRemaining(Request $request)
    {
        $user = \JWTAuth::toUser($request->token);
        if ($user == null) {
            return ApiHelper::responseClientFail(__('messages.user_exists_0'));
        }
        $employee = Employee::with('project_managers')
            ->whereNull('deleted_at')
            ->where('user_id', '=', $user->id)
            ->first();
        if ($employee == null) {
            return ApiHelper::responseClientFail(__('messages.employee_exists_0'));
        }
        $isPm = ApiHelper::isPM($employee->id);
        $isTeamLeader = ApiHelper::isTeamLeader($employee->id);
        $projectManagers = $employee->project_managers;
        $projectManager = $projectManagers != null && count($projectManagers) == 0 ? null : $projectManagers[0];
        $projectManager = $isPm ? null : $projectManager;
        $teamLeader = $isTeamLeader ? null : ApiHelper::getTeamLeader($employee->department_id);
        $boms = array();
        if ($isTeamLeader) {
            $tmpBoms = ApiHelper::getBoms();
            foreach ($tmpBoms as $tmpBom) {
                array_push($boms, [
                    'name' => $tmpBom->full_name,
                    'email' => $tmpBom->email
                ]);
            }
        }
        $day_off_permit_remain = 0;
        $day_off_ot_remain = 0;
        $accumulatedYear = TimeOnAccumulatedYear::query()
            ->where('year', '=', Carbon::now()->year)
            ->where('employee_id', '=', $employee->id)
            ->first();
        if ($accumulatedYear !== null) {
            $day_off_permit_remain = $accumulatedYear->day_off_remain_permit;
            $day_off_ot_remain = $accumulatedYear->day_off_remain_ot;
        }
        $result = [
            'day_off_accumulated_permit_temp' => $day_off_permit_remain,
            'day_off_accumulated_ot_temp' => $day_off_ot_remain,
            'project_manager' => [
                'name' => $projectManager == null ? '' : $projectManager->full_name,
                'email' => $projectManager == null ? '' : $projectManager->email,
            ],
            'team_leader' => [
                'name' => $teamLeader == null ? '' : $teamLeader->full_name,
                'email' => $teamLeader == null ? '' : $teamLeader->email,
            ],
            'boms' => $boms,
        ];
        return ApiHelper::responseSuccess('TimeOff remaining', ['result' => $result]);
    }

    public function getHomepageInfo()
    {
        if ($this->isAdmin()) {
            return ApiHelper::responseClientFail('Không có dữ liệu admin');
        }
        $userId = \Auth::id();
        $employee = Employee::query()->where('user_id', '=', $userId)
            ->first();
        $day = Carbon::now()->day;
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $time_on = TimeOnMonth::query()->where('employee_id', '=', $employee->id)
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->first();
        $day_off = 0;
        $day_off_permit_remain = 0;
        $day_off_ot_remain = 0;
        if ($time_on !== null && $employee !== null) {
            $day_off = $time_on->day_off;
        }
        if ($employee !== null) {
            $accumulatedYear = TimeOnAccumulatedYear::query()
                ->where('year', '=', $year)
                ->where('employee_id', '=', $employee->id)
                ->first();
            if ($accumulatedYear !== null) {
                $day_off_permit_remain = $accumulatedYear->day_off_remain_permit;
                $day_off_ot_remain = $accumulatedYear->day_off_remain_ot;
            }
        }

        $day_off_number = TimeOff::query()->where('employee_id', '=', $employee->id)
            ->whereNull('deleted_at')
            ->where('approved', '=', 1)
            ->get()->count();

        return ApiHelper::responseSuccess('List Employee', [
            'info' => [
                'day_off' => $day_off,
                'day_off_number' => $day_off_number,
                'day_off_permit_remain' => $day_off_permit_remain,
                'day_off_ot_remain' => $day_off_ot_remain,
                'month' => $month,
                'year' => $year,
                'day' => $day,
                'date' => Carbon::now()->toDateString()
            ]
        ]);
    }
}
