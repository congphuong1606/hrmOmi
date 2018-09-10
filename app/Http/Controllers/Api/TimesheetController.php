<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Helpers\MailHelper;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\TimeOff;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Validator;

class TimesheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return string
     */
    public function indexTimeOff(Request $request)
    {
        // for admin and manager
        $validator = Validator::make($request->all(), [
            'search_data' => 'min:0|max:191|nullable',
            'status' => 'array|nullable',
            'status.*' => 'integer|min:1|max:6|nullable',
            'approved' => 'integer|min:0|max:2|nullable',
            'month' => 'integer|min:1|max:12|nullable',
            'year' => 'integer|nullable',
            'limit' => 'integer|nullable',
            'page' => 'integer|nullable',

        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), ['error' => $validator->errors()->all()]);
        }
        $search_data = $request->input('search_data');
        $status = $request->input('status');
        $approved = $request->input('approved');
        $month = $request->input('month');
        $year = $request->input('year');
        $limit = $request->input('limit');
        $page = $request->input('page');
        $limit = $limit == null ? DEFAULT_PAGE_LIMIT : $limit;
        $page = $limit == null ? DEFAULT_DISPLAY_PAGE : $page;
        $timeOff = TimeOff::query();
        $timeOff->whereNull('deleted_at');
        if ($month != null && $year != null) {
            $timeOff->whereMonth('start_datetime', '=', $month)
                ->whereYear('start_datetime', '=', $year);
        }
        $timeOff->whereHas('employee', function ($query) use ($search_data) {
            if ($search_data) {
                $query->where('full_name', 'like', "%$search_data%")->orWhereHas('user', function ($query) use ($search_data) {
                    $query->where('email', 'like', "%$search_data%");
                });
            }

        });
        $timeOff->with('employee', 'employee.user');

        if (!empty($status)) {
            $status = array_filter($status);
            $status = array_unique($status);
            $timeOff->whereIn('status', $status);
        }
        if ($approved != null) {
            $timeOff->where('approved', '=', $approved);
        }
        $paginatedData = $timeOff->orderBy('start_datetime', 'desc')->paginate($limit, ['*'], 'page', $page);
        $timeOffs = array();
        foreach ($paginatedData as $item) {
            $employee = $item->employee;
            $user = $item->employee->user;
            array_push($timeOffs, [
                'id' => $item->id,
                'full_name' => $employee->full_name,
                'email' => $user == null ? __('messages.null') : $user->email,
                'start_datetime' => $item->start_datetime,
                'end_datetime' => $item->end_datetime,
                'status' => $item->status,
                'approved' => $item->approved,
                'approved_reason' => $item->approved_reason,
                'reason' => $item->reason,
                'detailed_reason' => $item->detailed_reason,
                'project_manger' => $item->project_manger,
                'team_leader' => $item->team_leader,
                'backup_person' => $item->backup_person,
                'forget_type' => $item->forget_type,
                'number_of_times' => ApiHelper::timeRangeComputation($item->start_datetime, $item->end_datetime, $item->status, $item->employee_id),
            ]);
        }
        $data = [
            'times_off' => $timeOffs,
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeTimeOff(Request $request)
    {
        //
        $user = JWTAuth::toUser($request->token);
        if ($user == null) {
            return ApiHelper::responseClientFail(__('messages.user_exists_0'));
        }
        $employee = Employee::whereNull('deleted_at')
            ->where('user_id', '=', $user->id)->first();
        if ($employee == null) {
            return ApiHelper::responseClientFail(__('messages.employee_exists_0'));
        }
        $setting = Setting::first();
        if ($setting == null) {
            return ApiHelper::responseFail(__('messages.setting_exists_0'));
        }
        $response = $this->storeTimeOffValidation($request, $employee, $setting);
        if ($response != null) {
            return $response;
        }
        $params = $request->only(['employee_id', 'start_datetime',
            'end_datetime', 'detailed_reason', 'status', 'reason', 'backup_person',
            'approved', 'approved_reason', 'flow_type']);
        switch ($params['status']) {
            case TYPE_IN_LATE:
                $startDatetime = $params['start_datetime'];
                $startDatetime = new DateTime($startDatetime);
                $standardStartMorning = $startDatetime->format('Y-m-d') . ' ' . $setting->start_morning;
                $standardEndMorning = $startDatetime->format('Y-m-d') . ' ' . $setting->end_morning;
                if (strtotime($params['start_datetime']) < strtotime($standardStartMorning)) {
                    return ApiHelper::responseClientFail(__('messages.time_off_start_datetime_invalid_1') . $setting->start_morning);
                }
                if (strtotime($params['start_datetime']) > strtotime($standardEndMorning)) {
                    return ApiHelper::responseClientFail(__('messages.time_off_start_datetime_invalid_2') . $setting->end_morning);
                }
                break;
            case TYPE_LEAVE_EARLY:
                $startDatetime = $params['end_datetime'];
                $startDatetime = new DateTime($startDatetime);
                $standardStartAfternoon = $startDatetime->format('Y-m-d') . ' ' . $setting->start_afternoon;
                $standardEndAfternoon = $startDatetime->format('Y-m-d') . ' ' . '18:00:00';
                if (strtotime($params['end_datetime']) > strtotime($standardEndAfternoon)) {
                    return ApiHelper::responseClientFail(__('messages.time_off_end_datetime_invalid_1') . '18:00:00');
                }
                if (strtotime($params['end_datetime']) < strtotime($standardStartAfternoon)) {
                    return ApiHelper::responseClientFail(__('messages.time_off_end_datetime_invalid_2') . $setting->start_afternoon);
                }
                break;
        }
        $exists = TimeOff::where('employee_id', '=', $employee->id)
            ->where('status', '=', $params['status'])
            ->whereDate('start_datetime', '=', date('Y-m-d', strtotime($params['start_datetime'])))
            ->first();
        if ($exists != null) {
            switch ($params['status']) {
                case TYPE_IN_LATE:
                    return ApiHelper::responseClientFail(__('messages.time_off_duplidate_in_late'));
                    break;
                case TYPE_ALL_DAY:
                    return ApiHelper::responseClientFail(__('messages.time_off_duplidate_all_day'));
                    break;
                case TYPE_LEAVE_EARLY:
                    return ApiHelper::responseClientFail(__('messages.time_off_duplidate_leave_early'));
                    break;
//                default:
//                    return ApiHelper::responseClientFail(__('messages.time_off_exists_1'));
            }

        }
        try {
            DB::beginTransaction();
            $timeOff = new TimeOff();
            $timeOff->fill($request->all());
            if (ApiHelper::isBom($employee->id)) {
                $timeOff->approved = APPROVED;
            }
            $timeOff->employee_id = $employee->id;
            $timeOff->start_datetime = date('Y-m-d H:i:s', strtotime($params['start_datetime']));
            $timeOff->end_datetime = date('Y-m-d H:i:s', strtotime($params['end_datetime']));

            $timeOff->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        try {
            $sendMail = MailHelper::sendEmailTimeOffRequest($timeOff, $timeOff->status);
            \Log::info($sendMail);
        } catch (\Exception $e) {
            \Log::info($e->getTraceAsString());
        }

        return ApiHelper::responseSuccess('Created Time Off', ['time_off' => $timeOff]);
    }

    public function storeTimeOffValidation(Request $request, $employee, $setting)
    {
        if ($employee == null) {
            return ApiHelper::responseClientFail(__('messages.employee_exists_0'));
        }
        $validator = Validator::make($request->all(), [
            'employee_id' => 'integer|nullable',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date',
            'detailed_reason' => 'required|string|min:0|max:191',
            'status' => 'required|integer|min:1|max:6',
            'reason' => 'string|min:0|max:191||nullable',
            'backup_person' => 'email|min:0|max:191|nullable',
            'approved' => 'integer|min:0|max:2',
            'approved_reason' => 'string|min:0|max:191|nullable',
            'flow_type' => 'required|boolean|nullable',
            'forget_type' => 'integer|nullable'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail(__('messages.validation_0'), ['error' => $validator->errors()->toArray()]);
        }
        if ($request->input('backup_person') != null) {
            $backupPerson = User::where('email', 'like', $request->input('backup_person'))->first();
            if ($backupPerson == null) {
                return ApiHelper::responseClientFail(__('messages.backup_person_exists_0'));
            }
        }
        if ($request->input('status') == TYPE_DID_NOT_CHECK_OUT_CHECK_IN) {
            if ($request->input('forget_type') === null) {
                return ApiHelper::responseClientFail('Thiếu kiểu loại quên chấm công');
            }
        }
        $start_datetime = $request->input('start_datetime');
        $end_datetime = $request->input('end_datetime');
        $requestStartDate = date('d-m-Y', strtotime($start_datetime));
        $requestEndDate = date('d-m-Y', strtotime($end_datetime));
        if (strtotime($start_datetime) > strtotime($end_datetime)) {
            return ApiHelper::responseClientFail(__('messages.start_end_datetime_invalid_2'));
        }
        $status = $request->input('status');
        if ($status < 6) {
            if ($requestStartDate != $requestEndDate) {
                return ApiHelper::responseClientFail(__('messages.start_end_datetime_invalid_0'));
            }
        } else {
            if ($requestStartDate == $requestEndDate) {
                return ApiHelper::responseClientFail(__('messages.start_end_datetime_invalid_1'));
            }
        }
        if ($status == TYPE_LEAVE_OUT || $status == TYPE_IN_LATE || $status == TYPE_LEAVE_EARLY) {
            if (date('w', strtotime($start_datetime)) == 0 || date('w', strtotime($start_datetime)) == 6) {
                return ApiHelper::responseClientFail(__('messages.time_off_weekend_0'));
            }
        }
        $now = (new DateTime())->format('Y-m-d H:i:s');
        $time_off_date = date('Y-m-d H:i:s', strtotime($start_datetime));
        $day = (strtotime($now) - strtotime($time_off_date)) / 60 / 60 / 24;
        if ($day > $setting->time_off_registration_threshold) {
            return ApiHelper::responseClientFail(__('messages.start_datetime_invalid_0'));
        }
        return null;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTimeOff(Request $request, $id)
    {
        //

        $validation = Validator::make($request->all(), [
            'detailed' => 'boolean|nullable'
        ]);
        if (!$validation->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), ['error' => $validation->errors()->all()]);
        }
        $detail = $request->input('detailed');
        $timeOff = TimeOff::whereNull('deleted_at')->whereHas('employee')->with('employee')->find($id);
        if ($timeOff == null) {
            return ApiHelper::responseClientFail(__('messages.time_off_exists_0'));
        }
        $employee = $timeOff->employee;

        unset($timeOff->employee);
        if ($detail) {
            $data = [
                'id' => $timeOff->id,
                'info' => [
                    'full_name' => $employee->full_name,
                    'email' => $employee->user != null ? $employee->user->email : null,
                    'birth_day' => $employee->birth_day != null ? date('d-m-Y', strtotime($employee->birth_day)) : null,
                ],
                'from_time' => $timeOff->start_datetime,
                'to_time' => $timeOff->end_datetime,
                'number_of_times' => ApiHelper::timeRangeComputation($timeOff->start_datetime, $timeOff->end_datetime, $timeOff->status, $timeOff->employee_id),
                'status' => $timeOff->status,
                'reason' => $timeOff->reason,
                'detailed_reason' => $timeOff->detailed_reason,
                'approved' => $timeOff->approved,
                'approved_reason' => $timeOff->approved_reason,
                'flow_type' => $timeOff->flow_type,
                'backup_person' => $timeOff->backup_person,
                'forget_type' => $timeOff->forget_type,
            ];
        } else {
            $timeOff->employee_full_name = $employee->full_name;
            $timeOff->employee_email = $employee->user != null ? $employee->user->email : null;
            $timeOff->number_of_times = ApiHelper::timeRangeComputation($timeOff->start_datetime, $timeOff->end_datetime, $timeOff->status, $timeOff->employee_id);
            $data = $timeOff;
        }
        return ApiHelper::responseSuccess('Time Off Information', ['time_off' => $data]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTimeOff(Request $request, $id)
    {
        //
//        return $id;
        $timeOff = TimeOff::where('deleted_at', '=', null)
            ->where('id', '=', $id)->first();
//        return $timeOff;
        $setting = Setting::first();
        $response = $this->updateTimeOffValidation($request, $timeOff, $setting);
        if ($response != null) {
            return $response;
        }
        $params = $request->only(['employee_id', 'start_datetime',
            'end_datetime', 'detailed_reason', 'status', 'reason', 'backup_person',
            'approved', 'approved_reason', 'flow_type']);

        $exists = TimeOff::where('employee_id', '=', $timeOff->employee_id)
            ->where('status', '=', $params['status'])
            ->whereDate('start_datetime', '=', date('Y-m-d', strtotime($params['start_datetime'])))
            ->first();
        if ($exists != null && $id != $exists->id) {
            switch ($params['status']) {
                case TYPE_IN_LATE:
                    return ApiHelper::responseClientFail(__('messages.time_off_duplidate_in_late'));
                    break;
                case TYPE_ALL_DAY:
                    return ApiHelper::responseClientFail(__('messages.time_off_duplidate_all_day'));
                    break;
                case TYPE_LEAVE_EARLY:
                    return ApiHelper::responseClientFail(__('messages.time_off_duplidate_leave_early'));
                    break;
//                default:
//                    return ApiHelper::responseClientFail(__('messages.time_off_exists_1'));
            }

        }
        try {
            DB::beginTransaction();
            $timeOff->fill($request->except('employee_id'));
            $now = (new DateTime())->format('Y:m:d H:i:s');
            $time_off_date = date('Y-m-d H:i:s', strtotime($params['start_datetime']));
            $day = (strtotime($now) - strtotime($time_off_date)) / 60 / 60 / 24;
            if ($day > $setting->time_off_registration_threshold) {
                $timeOff->approved = REFUSED;
                $timeOff->approved_reason = EXPIRE_MASSAGE;
            }
            $timeOff->start_datetime = date('Y-m-d H:i:s', strtotime($params['start_datetime']));
            $timeOff->end_datetime = date('Y-m-d H:i:s', strtotime($params['end_datetime']));


            $timeOff->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        try {
            $sendMail = MailHelper::sendEmailTimeOffRequest($timeOff, $timeOff->status);
            \Log::info($sendMail);
        } catch (\Exception $e) {
            \Log::info($e->getTraceAsString());
        }
        return ApiHelper::responseSuccess('Updated Time Off Information', ['time_off' => $timeOff]);
    }

    public function updateTimeOffValidation(Request $request, $timeOff, $setting)
    {
        if ($timeOff == null) {
            return ApiHelper::responseClientFail(__('messages.time_off_exists_0'));
        }
        if ($setting == null) {
            return ApiHelper::responseFail(__('messages.setting_exists_0'));
        }
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|integer',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date',
            'detailed_reason' => 'string|min:0|max:191',
            'backup_person' => 'email|min:0|max:191|nullable',
            'status' => 'integer|min:1|max:6',
            'approved' => 'integer|min:0|max:2',
            'approved_reason' => 'string|min:0|max:191|nullable',
            'reason' => 'string|min:0|max:191|nullable',
            'flow_type' => 'boolean',
            'forget_type' => 'integer|nullable'
        ]);

        if (!$validator->passes()) {
            ApiHelper::responseClientFail('Error Validate', ['error' => $validator->errors()->toArray()]);
        }
        if ($request->input("backup_person") !== null) {
            $backupPerson = User::where('email', 'like', $request->input('backup_person'))->first();
            if ($backupPerson == null) {
                return ApiHelper::responseClientFail(__('messages.backup_person_exists_0'));
            }
        }
        if ($request->input('status') == TYPE_DID_NOT_CHECK_OUT_CHECK_IN) {
            if ($request->input('forget_type') === null && $timeOff->forget_type === null) {
                return ApiHelper::responseClientFail('Thiếu kiểu loại quên chấm công');
            }
        }
        $employee = Employee::with('lateReason')->whereNull('deleted_at')
            ->find($request->input('employee_id'));
        if ($employee == null) {
            return ApiHelper::responseClientFail(__('messages.employee_exists_0'));
        }
        $start_datetime = $request->input('start_datetime');
        $end_datetime = $request->input('end_datetime');
        $requestStartDate = date('d-m-Y', strtotime($start_datetime));
        $requestEndDate = date('d-m-Y', strtotime($end_datetime));

        $status = $request->input('status');
        if ($status < 6) {
            if ($requestStartDate != $requestEndDate) {
                return ApiHelper::responseClientFail(__('messages.start_end_datetime_invalid_0'));
            }
        } else {
            if ($requestStartDate == $requestEndDate) {
                return ApiHelper::responseClientFail(__('messages.start_end_datetime_invalid_1'));
            }
        }
        if (strtotime($start_datetime) > strtotime($end_datetime)) {
            return ApiHelper::responseClientFail(__('messages.start_end_datetime_invalid_2'));
        }
        return null;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroyTimeOff($id)
    {
        //
        try {
            DB::beginTransaction();
            $timeOff = TimeOff::whereNull('deleted_at')->find($id);
            if ($timeOff == null) {
                return ApiHelper::responseClientFail('Đơn nghỉ phép không tồn tại');
            }
            $timeOff->deleted_at = new DateTime();
            $timeOff->save();
            Notification::where('time_off_id', '=', $id)->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Deleted Time Off', ['time_off_id' => $timeOff->id]);
    }

//
    public function getApproveTimeOff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'approved' => 'integer|min:0|max:2|nullable',
            'limit' => 'integer|nullable',
            'page' => 'integer|nullable',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), ['error' => $validator->errors()->all()]);
        }
        $limit = $request->input('limit');
        $page = $request->input('page');
        $limit = $limit != null ? $limit : DEFAULT_PAGE_LIMIT;
        $page = $page != null ? $page : DEFAULT_DISPLAY_PAGE;
        $approved = $request->input('approved');

        $user = JWTAuth::toUser($request->token);
        if ($user == null) {
            return ApiHelper::responseClientFail(__('messages.user_exists_0'));
        }
        $employee = Employee::whereNull('deleted_at')->where('user_id', '=', $user->id)->first();
        if ($employee == null) {
            return ApiHelper::responseClientFail(__('messages.employee_exists_0'));
        }

        $isBom = ApiHelper::isBom($employee->id);
        $isPM = ApiHelper::isPM($employee->id);
        $isTeamleader = ApiHelper::isTeamLeader($employee->id);

        if ($isBom) {
            \Log::info('getApproveTimeOff : isBom');
            $timeOffs = TimeOff::with('employee', 'employee.user')
                ->whereNull('deleted_at')->whereHas('employee', function ($query) use ($employee) {
                    $query->whereNull('deleted_at')->whereHas('user', function ($query) {
                        $query->whereHas('roles', function ($query) {
                            $query->where('name', 'like', SPECIFIC_ROLE_TEAM_LEADER);
                        });
                    });
                });
        } else if ($isTeamleader) {
            \Log::info('getApproveTimeOff : $isTeamleader');
            $timeOffs = TimeOff::with('employee')
                ->whereNull('deleted_at')->whereHas('employee', function ($query) use ($employee) {
                    $query->where(function ($query) use ($employee) {
                        $query->whereNull('deleted_at')->whereDoesntHave('project_managers')
                            ->where('department_id', '=', $employee->department_id);
                    })->orWhere(function ($query) {
                        $query->whereNull('deleted_at')->whereHas('user', function ($query) {
                            $query->whereHas('roles', function ($query) {
                                $query->where('name', 'like', SPECIFIC_ROLE_PROJECT_MANAGER);
                            });
                        });
                    });
                });
        } else if ($isPM) {
            \Log::info('getApproveTimeOff : $isPM');
            $timeOffs = TimeOff::with('employee', 'employee.user')
                ->whereNull('deleted_at')->whereHas('employee', function ($query) use ($employee) {
                    $query->whereHas('project_managers', function ($query) use ($employee) {
                        $query->whereNull('deleted_at')->where('id', '=', $employee->id);
                    });
                });
        } else {
            return ApiHelper::responseSuccess('List Time Off', [
                'times_off' => [],
                'pagination' => [
                    'total' => 0,
                    'per_page' => $limit,
                    'current_page' => $page,
                    'last_page' => 1
                ]
            ]);
        }

        if ($approved != null) {
            $timeOffs->where('approved', '=', $approved);
        }

        $paginatedData = $timeOffs->orderBy('created_at', 'desc')->paginate($limit, ['*'], 'page', $page);
        $timeOffs = array();
        foreach ($paginatedData as $item) {
            $employee = $item->employee;
            array_push($timeOffs, [
                'id' => $item->id,
                'info' => [
                    'full_name' => $employee->full_name,
                    'email' => $employee->user != null ? $employee->user->email : null,
                    'birth_day' => $employee->birth_day != null ? date('d-m-Y', strtotime($employee->birth_day)) : null,
                ],
                'from_time' => $item->start_datetime,
                'to_time' => $item->end_datetime,
                'number_of_times' => ApiHelper::timeRangeComputation($item->start_datetime, $item->end_datetime, $item->status, $item->employee_id),
                'status' => $item->status,
                'reason' => $item->reason,
                'detailed_reason' => $item->detailed_reason,
                'backup_person' => $item->backup_person,
                'approved' => $item->approved,
                'forget_type' => $item->forget_type,
            ]);
        }
        $data = [
            'times_off' => $timeOffs,
            'pagination' => [
                'total' => $paginatedData->total(),
                'per_page' => $paginatedData->perPage(),
                'current_page' => $paginatedData->currentPage(),
                'last_page' => $paginatedData->lastPage()
            ]
        ];
        return ApiHelper::responseSuccess('List Times Off', $data);
    }

    private function deleteRelatedNotification($time_off_ids)
    {
        try {
            DB::beginTransaction();
            Notification::where(function ($query) {
                $query->where('type', '=', NOTIFICATION_TYPE_TIME_OFF_APPROVED)
                    ->orWhere('type', '=', NOTIFICATION_TYPE_TIME_OFF_REFUSED);
            })->whereIn('time_off_id', $time_off_ids)->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info($e->getTraceAsString());
        }
    }

    private function handleTimeOff()
    {

    }

    public function approveTimeOff(Request $request)
    {
        $app = JWTAuth::toUser($request->token);
        if ($app == null) {
            return ApiHelper::responseClientFail(__('messages.user_exists_0'));
        }

        $time_off_ids = $request->input('time_off_ids');
        $approved_reason = $request->input('approved_reason');
        $validator = Validator::make($request->all(), [
            'time_off_ids' => 'required|array',
            'time_off_ids.*' => 'integer'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validator', ['error' => $validator->errors()->messages()]);
        }
        $time_off_ids = array_unique($time_off_ids);
        $time_offs = TimeOff::query();
        foreach ($time_off_ids as $time_off_id) {
            $time_offs->orWhere('id', '=', $time_off_id);
        }
        $time_offs = $time_offs->get();
        $approved_time_off_ids = array();
        try {
            DB::beginTransaction();
            foreach ($time_offs as $time_off) {
                $time_off->approved = APPROVED;
                $time_off->approved_reason = $approved_reason;
                $time_off->save();
                array_push($approved_time_off_ids, $time_off->id);
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseClientFail($e->getMessage(), $e->getTrace());
        }
        $approver = \JWTAuth::toUser($request->token);
        $approverName = $approver == null ? __('messages.null') : $approver->name;
        $this->deleteRelatedNotification($time_off_ids);
        foreach ($time_offs as $time_off) {
            MailHelper::sendMailApproveTimeOff($time_off, $approverName);
        }
        return ApiHelper::responseSuccess('Approved', ['time_off_ids' => $approved_time_off_ids]);
    }


    public function refuseTimeOff(Request $request)
    {
        $app = JWTAuth::toUser($request->token);
        if ($app == null) {
            return ApiHelper::responseClientFail(__('messages.user_exists_0'));
        }

        $time_off_ids = $request->input('time_off_ids');
        $approved_reason = $request->input('approved_reason');
        $validator = Validator::make($request->all(), [
            'time_off_ids' => 'required|array',
            'time_off_ids.*' => 'integer',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validator', ['error' => $validator->errors()->messages()]);
        }
        $time_off_ids = array_unique($time_off_ids);
        $time_offs = TimeOff::query();
        foreach ($time_off_ids as $time_off_id) {
            $time_offs->orWhere('id', '=', $time_off_id);
        }
        $time_offs = $time_offs->get();
        $refused_time_off_ids = array();
        try {
            DB::beginTransaction();
            foreach ($time_offs as $time_off) {
                $time_off->approved = REFUSED;
                $time_off->approved_reason = $approved_reason;
                $time_off->save();
                array_push($refused_time_off_ids, $time_off->id);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseClientFail($e->getMessage(), $e->getTrace());
        }
        $approver = \JWTAuth::toUser($request->token);
        $approverName = $approver == null ? __('messages.null') : $approver->name;
        $this->deleteRelatedNotification($time_off_ids);
        foreach ($time_offs as $time_off) {
            MailHelper::sendMailApproveTimeOff($time_off, $approverName);
        }
        return ApiHelper::responseSuccess('Refused', ['time_off_ids' => $refused_time_off_ids]);
    }


}
