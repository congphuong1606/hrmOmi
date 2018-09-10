<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use DB;
use Illuminate\Http\Request;
use Validator;

class DepartmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $department = Department::where('deleted_at', '=', null)->get();

        return ApiHelper::responseSuccess('List Department', ['departments' => $department]);
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
            'code'=>'required',
            'name' => 'required',
            'description' => 'required'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail(__('messages.validation_0'), ['error' => $validator->errors()->messages()]);
        }
        $department = new Department();
        $department->fill($request->all());

        if ($department->save()) {
            return ApiHelper::responseSuccess('Created Department', ['department' => $department]);
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
        $department = Department::where('deleted_at', '=', null)
            ->where('id', '=', $id)->first();

        return ApiHelper::responseSuccess('Department Infomation', ['department' => $department]);
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
        $department = Department::where('deleted_at', '=', null)
            ->where('id', '=', $id)->first();
        if ($department == null) {
            return ApiHelper::responseClientFail('Department doesn\'t exist');
        }
        try {
            DB::beginTransaction();

            $department->fill($request->all());
            $department->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }

        return ApiHelper::responseSuccess('Updated Department', ['department' => $department]);

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
            $department = Department::whereNull('deleted_at')->find($id);
            $employee = Employee::whereHas('department', function ($q) use ($id) {
                $q->where('id', '=', $id);
            })->first();
            if ($employee) {
                return ApiHelper::responseClientFail(__('messages.department_used_1'));
            }
            $department->deleted_at = new \DateTime();
            $department->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return ApiHelper::responseSuccess('Deleted Department', ['department_id' => $department->id]);
    }
}
