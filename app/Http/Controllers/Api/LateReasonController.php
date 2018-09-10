<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Models\Employee;
use App\Models\LateReason;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class LateReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $lateReasons = LateReason::whereNull('deleted_at')->get();
        return ApiHelper::responseSuccess('List Late Reason', ['late_reasons' => $lateReasons]);
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
            'name' => 'required|min:0|max:191',
            'description' => 'required|min:0|max:191',
            'start_morning' => 'required|date_format:H:i',
            'end_morning' => 'required|date_format:H:i',
            'start_afternoon' => 'required|date_format:H:i',
            'end_afternoon' => 'required|date_format:H:i',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), ['error' => $validator->errors()->all()]);
        }
        $start_morning = strtotime($request->input('start_morning'));
        $end_morning = strtotime($request->input('end_morning'));
        $start_afternoon = strtotime($request->input('start_afternoon'));
        $end_afternoon = strtotime($request->input('end_afternoon'));
        if ($start_morning > $end_morning || $end_morning > $start_afternoon || $start_afternoon > $end_afternoon) {
            return ApiHelper::responseClientFail(__('messages.time_invalid'));
        }
        try {
            DB::beginTransaction();
            $lateReason = new LateReason();
            $lateReason->fill($request->all());
            $lateReason->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Created Late Reason', ['late_reason' => $lateReason]);
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
        $lateReason = LateReason::whereNull('deleted_at')->find($id);
        return ApiHelper::responseSuccess('Late Reason Information', ['late_reason' => $lateReason]);
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
        $validator = Validator::make($request->all(), [
            'name' => 'min:0|max:191',
            'description' => 'min:0|max:191',
            'start_morning' => 'date_format:H:i',
            'end_morning' => 'date_format:H:i',
            'start_afternoon' => 'date_format:H:i',
            'end_afternoon' => 'date_format:H:i',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), ['error' => $validator->errors()->all()]);
        }
        $start_morning = strtotime($request->input('start_morning'));
        $end_morning = strtotime($request->input('end_morning'));
        $start_afternoon = strtotime($request->input('start_afternoon'));
        $end_afternoon = strtotime($request->input('end_afternoon'));
        if ($start_morning != null && $end_morning != null && $start_afternoon != null && $end_afternoon != null) {
            if ($start_morning > $end_morning || $end_morning > $start_afternoon || $start_afternoon > $end_afternoon) {
                return ApiHelper::responseClientFail(__('messages.time_invalid'));
            }
        }
        $lateReason = LateReason::whereNull('deleted_at')->find($id);
        if ($lateReason == null) {
            return ApiHelper::responseClientFail(__('messages.late_reason_exists_0'));
        }
        try {
            DB::beginTransaction();
            $lateReason->fill($request->all());
            $lateReason->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Updated Late Reason', ['late_reason' => $lateReason]);
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
            $lateReason = LateReason::whereNull('deleted_at')->find($id);
            $employee = Employee::whereHas('lateReason', function ($q) use ($id) {
                $q->where('id', '=', $id);
            })->first();
            if ($employee) {
                return ApiHelper::responseClientFail(__('messages.late_reason_used_1'));
            }
            $lateReason->deleted_at = new \DateTime();
            $lateReason->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Deleted Late Reason', ['late_reason_id' => $lateReason->id]);
    }
}
