<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\JobStatus;
use DB;
use Illuminate\Http\Request;
use Validator;

class JobStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $job_status = JobStatus::where('deleted_at', '=', null)->get();

        return ApiHelper::responseSuccess('List Job Status', ['jobs_status' => $job_status]);
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
            $job_status = new JobStatus();
            $job_status->fill($request->all());
            $job_status->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }

        return ApiHelper::responseSuccess('Created Job Status', ['job_status' => $job_status]);
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
        $job_status = JobStatus::where('deleted_at', '=', null)
            ->where('id', '=', $id)->first();

        return ApiHelper::responseSuccess('Job Status Infomation', ['job_status' => $job_status]);
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
        $job_status = JobStatus::where('deleted_at', '=', null)
            ->where('id', '=', $id)->first();
        if ($job_status == null) {
            return ApiHelper::responseClientFail(__('messages.job_status_exists_0'));
        }
        try {
            DB::beginTransaction();
            $job_status->fill($request->all());
            $job_status->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Updated Job Status', ['job_status' => $job_status]);

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
            $job_status = JobStatus::whereNull('deleted_at')->find($id);
            $employee = Employee::whereHas('jobStatus', function ($q) use ($id) {
                $q->where('id', '=', $id);
            })->first();
            if ($employee) {
                return ApiHelper::responseClientFail(__('messages.job_status_used_1'));
            }
            $job_status->deleted_at = new \DateTime();
            $job_status->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return ApiHelper::responseSuccess('Deleted Job Status', ['job_status_id' => $job_status->id]);
    }
}
