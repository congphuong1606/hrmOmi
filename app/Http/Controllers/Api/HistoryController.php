<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeUpdateHistory;
use App\Models\JobStatusUpdateHistory;
use App\Models\User;
use App\Services\TimeOnCalculating;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Validator;

class HistoryController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexEmployeeUpdate(Request $request)
    {
        $paginated_data = null;
        $data = null;

        $limit = $this->normalizeLimit($request->input('limit'));
        $page = $this->normalizePage($request->input('page'));

        $subQuery = EmployeeUpdateHistory::orderBy('created_at', 'DESC')
            ->groupBy('employee_id')
            ->select(['employee_id', DB::raw('MAX(created_at) as max_created_at')]);

        $query = EmployeeUpdateHistory::with('jobStatus', 'employee', 'workingStatus', 'position', 'department')
            ->where('status', '<>', DELETE_STATUS)
            ->where('approved', '=', 0)
            ->join(DB::raw("({$subQuery->toSql()}) as sub"), function ($join) {
                $join->on('employees_update_history.employee_id', '=', 'sub.employee_id');
                $join->on('employees_update_history.created_at', '=', 'sub.max_created_at');
            })
            ->mergeBindings($subQuery->getQuery())
            ->orderBy('employees_update_history.created_at', 'DESC')
            ->paginate($limit, ['*'], 'page', $page);
        $employees = $query->items();

        $data = [
            'employees' => $employees,
            'pagination' => [
                'total' => $query->total(),
                'per_page' => $query->perPage(),
                'current_page' => $query->currentPage(),
                'last_page' => $query->lastPage()
            ]
        ];
        return ApiHelper::responseSuccess('List Employee', $data);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showEmployeeUpdate($id)
    {
        //
        $employee = EmployeeUpdateHistory::with(['department', 'position', 'jobStatus', 'workingStatus', 'lateReason',
            'employee' => function ($q) {
                $q->with('department', 'position', 'jobStatus', 'workingStatus' , 'specialized_skills', 'attach_files', 'lateReason');
            }])
            ->where('id', '=', $id)->first();

        return ApiHelper::responseSuccess('Show Employee ', ['employee' => $employee]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEmployeeUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
        ], [
            'ids.required' => 'id không được bỏ trống.',
            'ids.array' => 'id không đúng định dạng',
        ]);

        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validate', [
                'error' => $validator->errors()->toArray()
            ]);
        }

        $ids = $request->input('ids');
        $employeeUpdates = EmployeeUpdateHistory::whereIn('id', $ids)
            ->get();
        $errors = [];
        foreach ($employeeUpdates as $value) {
            if ($value->approved === EMPLOYEE_UPDATE_APPROVE_APPROVED) {
                array_push($errors, [
                    'id' => $value->id,
                    'approve' => EMPLOYEE_UPDATE_APPROVE_APPROVED,
                    'message' => 'Thay đổi đã được duyệt'
                ]);
            }
            if ($value->approved === EMPLOYEE_UPDATE_APPROVE_REJECTED) {
                array_push($errors, [
                    'id' => $value->id,
                    'approve' => EMPLOYEE_UPDATE_APPROVE_REJECTED,
                    'message' => 'Thay đổi đã bị từ chối'
                ]);
            }
        }
        if (sizeof($errors)) {
            return ApiHelper::responseClientFail('Failed Validation', [
                'errors' => $errors
            ]);
        }
        try {
            DB::beginTransaction();
            foreach ($employeeUpdates as $value) {
                $value->aprroved = EMPLOYEE_UPDATE_APPROVE_APPROVED;
                $value->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail();
        }
        return ApiHelper::responseSuccess('Updated Employee Information', ['ids' => $ids]);
    }

    public function approveListEmployeeUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
        ], [
            'ids.required' => 'id không được bỏ trống.',
            'ids.array' => 'id không đúng định dạng',
        ]);

        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validate', [
                'error' => $validator->errors()->toArray()
            ]);
        }

        $ids = $request->input('ids');
        $employeeUpdates = EmployeeUpdateHistory::whereIn('id', $ids)
            ->get();
        $errors = [];
        foreach ($employeeUpdates as $value) {
            if ($value->approved === EMPLOYEE_UPDATE_APPROVE_APPROVED) {
                array_push($errors, [
                    'id' => $value->id,
                    'approve' => EMPLOYEE_UPDATE_APPROVE_APPROVED,
                    'message' => 'Thay đổi đã được duyệt'
                ]);
            }
            if ($value->approved === EMPLOYEE_UPDATE_APPROVE_REJECTED) {
                array_push($errors, [
                    'id' => $value->id,
                    'approve' => EMPLOYEE_UPDATE_APPROVE_REJECTED,
                    'message' => 'Thay đổi đã bị từ chối'
                ]);
            }
        }
        if (sizeof($errors)) {
            return ApiHelper::responseClientFail('Failed Validation', [
                'errors' => $errors
            ]);
        }
        try {
            DB::beginTransaction();
            $arr = [];
            foreach ($employeeUpdates as $value) {
                $value->approved = EMPLOYEE_UPDATE_APPROVE_APPROVED;
                $value->save();
                $employee = Employee::find($value->employee_id);
                $employee->full_name = $value->full_name;
                $employee->department_id = $value->department_id;
                $employee->position_id = $value->position_id;
                $employee->job_status_id = $value->job_status_id;
                $employee->birth_day = $value->birth_day;
                $employee->identification_number = $value->identification_number;
                $employee->identification_date = $value->identification_date;
                $employee->identification_place_of = $value->identification_place_of;
                $employee->tax_code = $value->tax_code;
                $employee->permanent_address = $value->permanent_address;
                $employee->temporary_address = $value->temporary_address;
                $employee->bank_number = $value->bank_number;
                $employee->bank_name = $value->bank_name;
                $employee->phone_number = $value->phone_number;
                $employee->chatwork_account = $value->chatwork_account;
                $employee->skype_account = $value->skype_account;
                $employee->facebook_link = $value->facebook_link;
                $employee->avatar = $value->getOriginal('avatar');
                $employee->employee_code = $value->employee_code;
                $employee->working_status_id = $value->working_status_id;
                $employee->late_reason_id = $value->late_reason_id;
                $employee->personal_email = $value->personal_email;
                $employee->update_date = $value->update_date;
                $employee->check_in_date = $value->check_in_date;
                $employee->training_date = $value->training_date;
                $employee->official_date = $value->official_date;
                $employee->bank_user_name = $value->bank_user_name;
                $employee->bank_branch = $value->bank_branch;
                $employee->contact_user = $value->contact_user;
                $employee->distance = $value->distance;
                $employee->save();
                if ($value->email !== null) {
                    $user = User::find($employee->user_id);
                    $user->email = $value->email;
                    $user->save();
                }
                array_push($arr, $employee->id);
            }
            TimeOnCalculating::calculatingAccumulatedYear(Carbon::now()->year, [$arr]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getTraceAsString());
        }
        return ApiHelper::responseSuccess('Updated Employee Information', ['ids' => $ids]);
    }

    public function rejectListEmployeeUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
        ], [
            'ids.required' => 'id không được bỏ trống.',
            'ids.array' => 'id không đúng định dạng',
        ]);

        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validate', [
                'error' => $validator->errors()->toArray()
            ]);
        }

        $ids = $request->input('ids');
        $employeeUpdates = EmployeeUpdateHistory::whereIn('id', $ids)
            ->get();
        $errors = [];
        foreach ($employeeUpdates as $value) {
            if ($value->approved === EMPLOYEE_UPDATE_APPROVE_APPROVED) {
                array_push($errors, [
                    'id' => $value->id,
                    'approve' => EMPLOYEE_UPDATE_APPROVE_APPROVED,
                    'message' => 'Thay đổi đã được duyệt'
                ]);
            }
            if ($value->approved === EMPLOYEE_UPDATE_APPROVE_REJECTED) {
                array_push($errors, [
                    'id' => $value->id,
                    'approve' => EMPLOYEE_UPDATE_APPROVE_REJECTED,
                    'message' => 'Thay đổi đã bị từ chối'
                ]);
            }
        }
        if (sizeof($errors)) {
            return ApiHelper::responseClientFail('Failed Validation', [
                'errors' => $errors
            ]);
        }
        try {
            DB::beginTransaction();
            foreach ($employeeUpdates as $value) {
                $value->approved = EMPLOYEE_UPDATE_APPROVE_REJECTED;
                $value->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage());
        }
        return ApiHelper::responseSuccess('Updated Employee Information', ['ids' => $ids]);
    }


    public function approveEmployeeUpdate(Request $request, $id)
    {
        $employeeUpdate = EmployeeUpdateHistory::find($id);
        if ($employeeUpdate === null) {
            return ApiHelper::responseClientFail('Not Found');
        }
        if ($employeeUpdate->approved === EMPLOYEE_UPDATE_APPROVE_APPROVED) {
            return ApiHelper::responseClientFail('Not Found', [
                'errors' => [
                    'id' => $id,
                    'approve' => EMPLOYEE_UPDATE_APPROVE_APPROVED,
                    'message' => 'Thay đổi đã được duyệt'
                ]
            ]);
        }

        if ($employeeUpdate->approved === EMPLOYEE_UPDATE_APPROVE_REJECTED) {
            return ApiHelper::responseClientFail('Not Found', [
                'errors' => [
                    'id' => $id,
                    'approve' => EMPLOYEE_UPDATE_APPROVE_REJECTED,
                    'message' => 'Thay đổi đã bị từ chối'
                ]
            ]);
        }

        try {
            DB::beginTransaction();
            $employeeUpdate->approved = EMPLOYEE_UPDATE_APPROVE_APPROVED;
            $employeeUpdate->save();
            $employee = Employee::find($employeeUpdate->employee_id);
            $employee->full_name = $employeeUpdate->full_name;
            $employee->department_id = $employeeUpdate->department_id;
            $employee->position_id = $employeeUpdate->position_id;
            $employee->job_status_id = $employeeUpdate->job_status_id;
            $employee->birth_day = $employeeUpdate->birth_day;
            $employee->identification_number = $employeeUpdate->identification_number;
            $employee->identification_date = $employeeUpdate->identification_date;
            $employee->identification_place_of = $employeeUpdate->identification_place_of;
            $employee->tax_code = $employeeUpdate->tax_code;
            $employee->permanent_address = $employeeUpdate->permanent_address;
            $employee->temporary_address = $employeeUpdate->temporary_address;
            $employee->bank_number = $employeeUpdate->bank_number;
            $employee->bank_name = $employeeUpdate->bank_name;
            $employee->phone_number = $employeeUpdate->phone_number;
            $employee->chatwork_account = $employeeUpdate->chatwork_account;
            $employee->skype_account = $employeeUpdate->skype_account;
            $employee->facebook_link = $employeeUpdate->facebook_link;
            $employee->avatar = $employeeUpdate->getOriginal('avatar');
            $employee->employee_code = $employeeUpdate->employee_code;
            $employee->working_status_id = $employeeUpdate->working_status_id;
            $employee->late_reason_id = $employeeUpdate->late_reason_id;
            $employee->personal_email = $employeeUpdate->personal_email;
            $employee->update_date = $employeeUpdate->update_date;
            $employee->check_in_date = $employeeUpdate->check_in_date;
            $employee->training_date = $employeeUpdate->training_date;
            $employee->official_date = $employeeUpdate->official_date;
            $employee->bank_user_name = $employeeUpdate->bank_user_name;
            $employee->bank_branch = $employeeUpdate->bank_branch;
            $employee->contact_user = $employeeUpdate->contact_user;
            $employee->distance = $employeeUpdate->distance;
            $employee->save();
            if ($employeeUpdate->email !== null) {
                $user = User::find($employee->user_id);
                $user->email = $employeeUpdate->email;
                $user->save();
            }
            TimeOnCalculating::calculatingAccumulatedYear(Carbon::now()->year, [$employee->id]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getTraceAsString());
        }
        return ApiHelper::responseSuccess('Updated Employee Information', ['id' => $id]);
    }

    public function rejectEmployeeUpdate(Request $request, $id)
    {
        $employeeUpdate = EmployeeUpdateHistory::find($id);
        if ($employeeUpdate === null) {
            return ApiHelper::responseClientFail('Not Found');
        }
        if ($employeeUpdate->approved === EMPLOYEE_UPDATE_APPROVE_APPROVED) {
            return ApiHelper::responseClientFail('Not Found', [
                'errors' => [
                    'id' => $id,
                    'approve' => EMPLOYEE_UPDATE_APPROVE_APPROVED,
                    'message' => 'Thay đổi đã được duyệt'
                ]
            ]);
        }

        if ($employeeUpdate->approved === EMPLOYEE_UPDATE_APPROVE_REJECTED) {
            return ApiHelper::responseClientFail('Not Found', [
                'errors' => [
                    'id' => $id,
                    'approve' => EMPLOYEE_UPDATE_APPROVE_REJECTED,
                    'message' => 'Thay đổi đã bị từ chối'
                ]
            ]);
        }

        try {
            DB::beginTransaction();
            $employeeUpdate->approved = EMPLOYEE_UPDATE_APPROVE_REJECTED;
            $employeeUpdate->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage());
        }
        return ApiHelper::responseSuccess('Updated Employee Information', ['id' => $id]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexJobStatusUpdate(Request $request)
    {
        //
        $limit = $request->input('limit');
        $page = $request->input('page');
        $paginated_data = null;
        $data = null;
        if ($limit) {
            if ($page) {
                $paginated_data = JobStatusUpdateHistory::with('jobStatus')->paginate($limit, ['*'], 'page', $page);
            } else {
                $paginated_data = JobStatusUpdateHistory::with('jobStatus')->paginate($limit, ['*'], 'page', DEFAULT_DISPLAY_PAGE);
            }
        } else {
            if ($page) {
                $paginated_data = JobStatusUpdateHistory::with('jobStatus')->paginate(DEFAULT_PAGE_LIMIT, ['*'], 'page', $page);
            } else {
                $paginated_data = JobStatusUpdateHistory::with('jobStatus')->paginate(DEFAULT_PAGE_LIMIT, ['*'], 'page', DEFAULT_DISPLAY_PAGE);
            }
        }
        $jobStatus = array();
        foreach ($paginated_data as $js) {
            array_push($jobStatus, $js);
        }
        $data = ['job_statuses' => $jobStatus
            , 'pagination' => ['total' => $paginated_data->total()
                , 'per_page' => $paginated_data->perPage()
                , 'current_page' => $paginated_data->currentPage()
                , 'last_page' => $paginated_data->lastPage()]

        ];

        return ApiHelper::responseSuccess('List Job Status Update', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showJobStatusUpdate($id)
    {
        //
        $jobStatus = JobStatusUpdateHistory::find($id);

        return ApiHelper::responseSuccess('Show Job Status Update ', ['job_status' => $jobStatus]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateJobStatusUpdate(Request $request, $id)
    {
        //
        $jobStatus = JobStatusUpdateHistory::find($id);

        $jobStatus->fill($request->all());
        if ($jobStatus->save()) {
            return ApiHelper::responseSuccess('Updated Job Status Update', ['job_status' => $jobStatus]);
        }
    }
}
