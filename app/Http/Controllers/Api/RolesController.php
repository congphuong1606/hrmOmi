<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Validator;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $roles = Role::all();
        $data = array();
        foreach ($roles as $role) {
            $userCount = Role::find($role->id)->users()->whereHas('employee', function ($query) {
                $query->whereNull('deleted_at');
            })->count();
            $role->user_count = $userCount;
            array_push($data, $role);
        }
        return ApiHelper::responseSuccess('List Roles', ['roles' => $data]);
    }

    /**
     * Display a listing of Role Permission
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoleUser($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inverse' => 'boolean|nullable',
            'limit' => 'integer|min:0|nullable',
            'page' => 'integer|min:0|nullable',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), ['error' => $validator->errors()->all()]);
        }
        $inverse = $request->input('inverse');
        $limit = $request->input('limit');
        $page = $request->input('page');
        $searchData = $request->input('search_data');
        $role = Role::find($id);
        if ($role == null) {
            return ApiHelper::responseClientFail(__('messages.role_exists_0'));
        }
        $searchedUser = $role->users()->whereHas('employee', function ($query) {
            $query->whereNull('deleted_at');
        })->distinct();
        if ($inverse == 1) {
            $query = User::query();
            foreach ($searchedUser->get() as $user) {
                $query->where('id', '<>', $user->id);
            }
            $query->whereHas('employee')->distinct();
            $searchedUser = $query;
        }

        if ($searchData) {
            $searchedUser->with('employee')->where(function ($query) use ($searchData) {
                $query->whereHas('employee', function ($q) use ($searchData) {
                    $q->where('employee_code', 'like', "%$searchData%");
                })->orWhere('email', 'like', "%$searchData%");
            });
        }
        $paginatedData = null;
        $limit = $limit == null ? DEFAULT_PAGE_LIMIT : $limit;
        $page = $page == null ? DEFAULT_DISPLAY_PAGE : $page;
        $paginatedData = $searchedUser->distinct()->paginate($limit, ['*'], 'page', $page);
        $users = array();
        foreach ($paginatedData as $user) {
            $user->employee_code = $user->employee->employee_code;
            $user->name = $user->employee->full_name;
            unset($user->employee);
            unset($user->pivot);
            array_push($users, $user);
        }

        $data = ['users' => $users
            , 'pagination' => ['total' => $paginatedData->total()
                , 'per_page' => $paginatedData->perPage()
                , 'current_page' => $paginatedData->currentPage()
                , 'last_page' => $paginatedData->lastPage()]
        ];
        return ApiHelper::responseSuccess('Role User', $data);
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
        $role = new Role();
        $role->fill($request->all());

        if ($role->save()) {
            return ApiHelper::responseSuccess('Created Role', ['role' => $role]);
        }
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
        $role = Role::find($id);

        return ApiHelper::responseSuccess('Role Infomation', ['role' => $role]);
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
        //
        $role = Role::find($id);
        $role->fill($request->all());

        if ($role->save()) {
            return ApiHelper::responseSuccess('Updated Role', ['role' => $role]);
        }
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
            $role = Role::find($id);
            $role->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Deleted Role', ['role_id' => $role->id]);
    }

    public function attachUsers($role_id, Request $request)
    {
        $user_ids = $request->input('user_ids');
        $user_ids = array_unique($user_ids);
        $role = Role::find($role_id);
        if ($role == null) {
            return ApiHelper::responseClientFail(__('messages.role_exists_0'));
        }
        $role->users()->whereHas('employee', function ($query) {
            $query->whereNull('deleted_at');
        })->sync($user_ids, false);
        return ApiHelper::responseSuccess('Attached Users', ['role_users' => ['role_id' => $role_id, 'users_id' => $user_ids]]);
    }

    public function detachUsers($role_id, Request $request)
    {
        $user_ids = $request->input('user_ids');
        $user_ids = array_unique($user_ids);
        $role = Role::find($role_id);
        if ($role == null) {
            return ApiHelper::responseClientFail(__('messages.role_exists_0'));
        }
        foreach ($user_ids as $user_id) {
            $role->users()->whereHas('employee', function ($query) {
                $query->whereNull('deleted_at');
            })->detach($user_id);
        }
        return ApiHelper::responseSuccess('Detached Users', ['role_users' => ['role_id' => $role_id, 'users_id' => $user_ids]]);
    }

    public function attachScreens($role_id, Request $request)
    {
        $role = Role::find($role_id);
        if ($role == null) {
            return ApiHelper::responseClientFail(__('messages.role_exists_0'));
        }
        $screens_id = $request->input('screen_ids');
        $screens_id = array_unique($screens_id);
        $role->screens()->sync($screens_id, true);
        return ApiHelper::responseSuccess('Attached Screen', ['role_screens' => ['role_id' => $role_id, 'screens_id' => $screens_id]]);
    }

    public function getRoleScreen($id)
    {
        $screens = Role::find($id)->screens()->whereNull('deleted_at')->get();
        return ApiHelper::responseSuccess('List Screen', ['screens' => $screens]);
    }
}
