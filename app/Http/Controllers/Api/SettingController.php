<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use DB;
use Illuminate\Http\Request;
use Validator;

class SettingController extends Controller
{
    //
    public function show()
    {
        $setting = Setting::first();
        return ApiHelper::responseSuccess('Setting Information', ['setting' => $setting]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_morning' => 'required|date_format:H:i',
            'end_morning' => 'required|date_format:H:i',
            'start_afternoon' => 'required|date_format:H:i',
            'end_afternoon' => 'required|date_format:H:i',
            'in_late_threshold' => 'required|integer|min:0',
            'time_off_registration_threshold' => 'required|integer|min:0',
            'hr_and_administration_mail' => 'required|email',
            'bom_mail' => 'required|email',
            'time_off_reset_milestone' => 'required|date_format:d-m',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), ['error' => $validator->errors()->all()]);
        }
        $setting = Setting::first();
        if ($setting == null) {
            return ApiHelper::responseClientFail(__('messages.setting_exists_0'));
        }

        $start_morning = strtotime($request->input('start_morning'));
        $end_morning = strtotime($request->input('end_morning'));
        $start_afternoon = strtotime($request->input('start_afternoon'));
        $end_afternoon = strtotime($request->input('end_afternoon'));

        if ($start_morning > $end_morning || $end_morning > $start_afternoon || $start_afternoon > $end_afternoon) {
            return ApiHelper::responseClientFail(__('messages.setting_time_invalid'));
        }

        try {
            DB::beginTransaction();
            $setting->fill($request->all());
            $setting->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Updated Setting', ['setting' => $setting]);
    }
}
