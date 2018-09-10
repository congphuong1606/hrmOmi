<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeProjectManager;
use App\Models\EmployeesAttachFiles;
use App\Models\EmployeesSpecializedSkills;
use App\Models\EmployeeUpdateHistory;
use App\Models\JobStatus;
use App\Models\JobStatusUpdateHistory;
use App\Models\Position;
use App\Models\Setting;
use App\Models\TimeOff;
use App\Models\TimeOn;
use App\Models\TimeOnMonth;
use App\Models\User;
use App\Models\WorkingStatus;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;

class EmployeeResourcesController extends Controller
{
    public function getListEmployees(Request $request)
    {
        if (!$this->isAdmin()) {
            $gm = Employee::query()
                ->where('user_id', '=', Auth::id())
                ->first();
            if ($gm === null) {
                return ApiHelper::responseClientFail('Người dùng không tồn tại');
            }
        }

        $gmUser = User::query()
            ->where('id', '=', Auth::id())
            ->with('roles')
            ->first();
        $isAdmin = false;
        $isGM = false;
        $isBom = false;
        foreach ($gmUser->roles as $value) {
            if (mb_strtolower($value->name) === 'team leader') {
                $isGM = true;
            }
            if (mb_strtolower($value->name) === 'admin') {
                $isAdmin = true;
            }
            if (mb_strtolower($value->name) === 'bom') {
                $isBom = true;
            }
        }
        if (!$isAdmin && !$isGM && !$isBom) {
            return ApiHelper::responseClientFail('Bạn không có quyền hạn để truy cập tài nguyên này');
        }
        $limit = $request->input('limit');
        $page = $request->input('page');
        if ($limit === null) {
            $limit = DEFAULT_PAGE_LIMIT;
        }
        if ($page === null) {
            $page = DEFAULT_DISPLAY_PAGE;
        }
        $working_status_id = $request->input('working_status_id');
        $job_status_id = $request->input('job_status_id');
        $position_id = $request->input('position_id');
        $department_id = $request->input('department_id');
        $employee_name = $request->input('employee_name');
        $project_manager_name = $request->input('project_manager_name');
        $direct_manager_name = $request->input('direct_manager_name');

        $departments = Department::query()
            ->with(['employees' => function ($q) {
                $q->whereHas('user', function ($q2) {
                    $q2->whereHas('roles', function ($q3) {
                        $q3->where('name', 'LIKE', 'team leader');
                    });
                });
            }])->get();
        if ($isAdmin || $isBom) {
            $employees = Employee::query()
                ->whereNull('deleted_at')
                ->with(['directManager', 'project_managers', 'user' => function ($q) {
                    $q->with('roles');
                }])
                ->whereHas('user', function ($q) {
                    $q->whereDoesntHave('roles', function ($q2) {
                        $q2->where('name', 'LIKE', 'team leader')
                            ->orWhere('name', 'LIKE', 'bom');
                    });
                });

        } else {
            $employees = Employee::query()
                ->whereNull('deleted_at')
                ->where('department_id', '=', $gm->department_id)
                ->with(['directManager', 'project_managers', 'user' => function ($q) {
                    $q->with('roles');
                }])
                ->whereHas('user', function ($q) {
                    $q->whereDoesntHave('roles', function ($q2) {
                        $q2->where('name', 'LIKE', 'team leader')
                            ->orWhere('name', 'LIKE', 'bom');
                    });
                });
        }
        if ($employee_name !== null) {
            $employees->where('full_name', 'LIKE', '%' . $employee_name . '%');
        }
        if ($project_manager_name !== null) {
            $employees->whereHas('project_managers', function ($q) use ($project_manager_name) {
                $q->where('full_name', 'LIKE', '%' . $project_manager_name . '%');
            });
        }
        if ($direct_manager_name !== null) {
            $employees->whereHas('directManager', function ($q) use ($direct_manager_name) {
                $q->where('full_name', 'LIKE', '%' . $direct_manager_name . '%');
            });
        }
        if ($working_status_id !== null && $working_status_id !== '0') {
            $employees->where('working_status_id', '=', $working_status_id);
        }
        if ($job_status_id !== null && $job_status_id !== '0') {
            $employees->where('job_status_id', '=', $job_status_id);
        }
        if ($position_id !== null && $position_id !== '0') {
            $employees->where('position_id', '=', $position_id);
        }
        if ($department_id !== null && $department_id !== '0') {
            $employees->where('department_id', '=', $department_id);
        }
        $paginated_data = $employees->paginate($limit, ['*'], 'page', $page);
        $items = $paginated_data->items();
        $arr = [];
        foreach ($items as $key => $value) {
            $temp = $value->toArray();
            $temp['is_project_manager'] = 0;
            $temp['team_leader_name'] = null;
            foreach ($temp['user']['roles'] as $role) {
                if ($role['name'] === 'project manager') {
                    $temp['is_project_manager'] = 1;
                }
            }
            foreach ($departments as $department) {
                if ($temp['department_id'] === $department->id) {
                    if (sizeof($department->employees))
                    $temp['team_leader_name'] = $department->employees[0]->full_name;
                }
            }
            array_push($arr, $temp);
        }
        $data = [
            'employees' => $arr,
            'pagination' => [
                'total' => $paginated_data->total(),
                'per_page' => $paginated_data->perPage(),
                'current_page' => $paginated_data->currentPage(),
                'last_page' => $paginated_data->lastPage()
            ]
        ];

        return ApiHelper::responseSuccess('List Employees', $data);
    }

    public function getListDirectManagers(Request $request)
    {
        $employees = Employee::query()
            ->with('department', 'position')
            ->whereHas('user', function ($q) {
                $q->whereHas('roles', function ($q2) {
//                    $q2->where('name', '=', 'team leader');
                });
            })->get();
        return ApiHelper::responseSuccess('List Direct Managers', ['direct_managers' => $employees]);
    }

    public function updateDirectManager(Request $request, $employee_id)
    {
        $direct_manager_id = $request->input('direct_manager_id');
        $employee = Employee::query()
            ->where('id', '=', $employee_id)
            ->first();
        if ($employee === null) {
            return ApiHelper::responseClientFail('Nhân viên không tồn tại');
        }
        try {
            DB::beginTransaction();
            $employee->direct_manager_id = $direct_manager_id;
            $employee->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseClientFail('Đã có lỗi xảy ra.');
        }
        $employee = Employee::query()
            ->where('id', '=', $employee_id)
            ->with(['directManager', 'project_managers'])
            ->first();
        return ApiHelper::responseSuccess('List Direct Managers', ['employee' => $employee]);
    }

    public function getListProjectManagers(Request $request)
    {
        $employees = Employee::query()
            ->with('department', 'position')
            ->whereHas('user', function ($q) {
                $q->whereHas('roles', function ($q2) {
                    $q2->where('name', '=', 'project manager');
                });
            })->get();
        return ApiHelper::responseSuccess('List Project Managers', ['project_managers' => $employees]);
    }

    public function updateProjectManager(Request $request, $employee_id)
    {
        $project_manager_ids = $request->input('project_manager_ids');
        $employee = Employee::query()
            ->where('id', '=', $employee_id)
            ->first();
        if ($employee === null) {
            return ApiHelper::responseClientFail('Nhân viên không tồn tại');
        }
        try {
            DB::beginTransaction();
            EmployeeProjectManager::query()
                ->where('employee_id', '=', $employee_id)
                ->delete();
            $project_manager_ids = array_unique($project_manager_ids);
            foreach ($project_manager_ids as $value) {
                if ($value !== 0) {
                    $employeeProjectManager = new EmployeeProjectManager();
                    $employeeProjectManager->employee_id = $employee_id;
                    $employeeProjectManager->project_manager_id = $value;
                    $employeeProjectManager->save();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseClientFail('Đã có lỗi xảy ra.');
        }
        $employee = Employee::query()
            ->where('id', '=', $employee_id)
            ->with(['directManager', 'project_managers'])
            ->first();
        return ApiHelper::responseSuccess('List Direct Managers', ['employee' => $employee]);
    }
}
