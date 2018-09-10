<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Models\OfficialHoliday;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class OfficialHolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $officialHolidays = OfficialHoliday::where('deleted_at', '=', null)->get();
        $data = array();
        foreach ($officialHolidays as $officialHoliday) {
            array_push($data,[
                'id'=>$officialHoliday->id,
                'start_date'=>date('d-m-Y',strtotime($officialHoliday->start_date)),
                'end_date'=>date('d-m-Y',strtotime($officialHoliday->end_date)),
                'description'=>$officialHoliday->description,
            ]);
        }
        return ApiHelper::responseSuccess('List Official Holidays', ['official_holidays' => $data]);
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
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'description' => 'required|min:0|max:191',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validation', ['error' => $validator->errors()->all()]);
        }
        $end_date = date('Y:m:d', strtotime($request->input('end_date')));
        $start_date = date('Y:m:d', strtotime($request->input('start_date')));
        $description = $request->input('description');
        try {
            DB::beginTransaction();
            $officialHoliday = new OfficialHoliday();
            $officialHoliday->start_date = $start_date;
            $officialHoliday->end_date = $end_date;
            $officialHoliday->description = $description;
            $officialHoliday->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Created Official Holiday', ['official_holiday' => $officialHoliday]);
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
        $officialHoliday = OfficialHoliday::where('deleted_at', '=', null)
            ->where('id', '=', $id)->first();
        return ApiHelper::responseSuccess('Official Holiday Infomation', ['official_holiday' => $officialHoliday]);
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
            'start_date' => 'date',
            'end_date' => 'date',
            'description' => 'min:0|max:191',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validation', ['error' => $validator->errors()->all()]);
        }
        $end_date = date('Y:m:d', strtotime($request->input('end_date')));
        $start_date = date('Y:m:d', strtotime($request->input('start_date')));
        $description = $request->input('description');
        try {
            DB::beginTransaction();
            $officialHoliday = OfficialHoliday::where('deleted_at', '=', null)
                ->where('id', '=', $id)->first();
            $officialHoliday->start_date = $start_date == null ? $officialHoliday->start_date : $start_date;
            $officialHoliday->end_date = $end_date == null ? $officialHoliday->end_date : $end_date;
            $officialHoliday->description = $description == null ? $officialHoliday->description : $description;
            $officialHoliday->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Updated Official Holiday', ['official_holiday' => $officialHoliday]);
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
            $officialHoliday = OfficialHoliday::where('deleted_at', '=', null)
                ->where('id', '=', $id)->first();
            $officialHoliday->deleted_at = new \DateTime();
            $officialHoliday->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Deleted Official Holiday', ['official_holiday' => $officialHoliday->id]);
    }
}
