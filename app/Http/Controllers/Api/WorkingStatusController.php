<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\WorkingStatus;
use DB;
use Illuminate\Http\Request;
use Validator;

class WorkingStatusController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $workingStatus = WorkingStatus::where('deleted_at', '=', null)->get();

        return ApiHelper::responseSuccess('List Working Status', ['working_status' => $workingStatus]);
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
            'name' => 'required',
            'description' => 'required',
            'code' => 'string|min:0|max:191|nullable',

        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validate', ['error' => $validator->errors()->messages()]);
        }
        try {
            DB::beginTransaction();
            $workingStatus = new WorkingStatus();
            $workingStatus->fill($request->all());
            $workingStatus->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Created Working Status', ['working_status' => $workingStatus]);

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
        $workingStatus = WorkingStatus::where('deleted_at', '=', null)
            ->where('id', '=', $id)->first();

        return ApiHelper::responseSuccess('Working Status Infomation', ['working_status' => $workingStatus]);
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
        $workingStatus = WorkingStatus::where('deleted_at', '=', null)
            ->where('id', '=', $id)->first();
        if ($workingStatus == null) {
            return ApiHelper::responseClientFail(__('messages.working_status_exists_0'));
        }
        try {
            DB::beginTransaction();
            $workingStatus->fill($request->all());
            $workingStatus->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Updated Working Status', ['working_status' => $workingStatus]);
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
            $workingStatus = WorkingStatus::whereNull('deleted_at')->find($id);
            $employee = Employee::whereHas('workingStatus', function ($q) use ($id) {
                $q->where('id', '=', $id);
            })->first();
            if ($employee) {
                return ApiHelper::responseClientFail(__('messages.working_status_used_1'));
            }
            $workingStatus->deleted_at = new \DateTime();
            $workingStatus->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return ApiHelper::responseSuccess('Deleted Working Status', ['working_status_id' => $workingStatus->id]);
    }
}
