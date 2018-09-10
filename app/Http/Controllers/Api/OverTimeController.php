<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Helpers\MailHelper;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\OtherCategory;
use App\Models\OverTime;
use App\Models\OverTimeDetail;
use App\Models\User;
use App\Services\TimeOnCalculating;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;

class OverTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        //
        $page = $request->input('page');
        $limit = $request->input('limit');
        $month = $request->input('month');
        $year = $request->input('year');
        $own = $request->input('own');
        $approved = $request->input('approved');
        $search_data = $request->input('search_data');
        $validator = Validator::make($request->all(), [
            'own' => 'boolean|nullable',
            'project_category_id' => 'integer|nullable',
            'search_data' => 'min:0|max:191|nullable',
            'approved' => 'integer|min:0|max:2|nullable',
            'month' => 'integer|min:1|max:12|nullable',
            'year' => 'integer|nullable',
            'limit' => 'integer|nullable',
            'page' => 'integer|nullable',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), ['error' => $validator->errors()->all()]);
        }
        if ($own == false || $own == null) {
            $overTimes = OverTime::with('otherCategory', 'details');
        } else {
            $user = \JWTAuth::toUser($request->token);
            if ($user == null) {
                return ApiHelper::responseClientFail(__('messages.user_exists_0'));
            }
            $employee = Employee::whereNull('deleted_at')->whereHas('user', function ($query) use ($user) {
                $query->where('id', '=', $user->id);
            })->first();
            if ($employee == null) {
                return ApiHelper::responseClientFail(__('messages.employee_exists_0'));
            }
            $overTimes = OverTime::with('otherCategory', 'details')
                ->where('proposer', '=', $user->email);
        }
        $overTimes->whereNull('deleted_at');
        if ($approved) {
            $overTimes->where('approved', '=', $approved);
        }
        if ($month != null && $year != null) {
            $overTimes->whereMonth('created_at', '=', $month)
                ->whereYear('created_at', '=', $year);
        }
        $project_category_id = $request->input('project_category_id');
        if ($project_category_id) {
            $overTimes->where('project_category_id', '=', $project_category_id);
        }

        if ($search_data) {
            $overTimes->where('proposer', 'like', "%$search_data%")
                ->orWhereHas('user', function ($query) use ($search_data) {
                    $query->where('name', 'like', "%$search_data%");
                });
        }
        $paginatedData = null;
        $limit = $limit != null ? $limit : DEFAULT_PAGE_LIMIT;
        $page = $page != null ? $page : DEFAULT_DISPLAY_PAGE;
        $paginatedData = $overTimes->orderBy('created_at', 'desc')->paginate($limit, ['*'], 'page', $page);
        $customItems = array();

        foreach ($paginatedData as $item) {
            $created_at = $item->created_at == null ? '' : Carbon::parse($item->created_at)->format('d-m-Y H:i');
            $updated_at = $item->created_at == null ? '' : Carbon::parse($item->updated_at)->format('d-m-Y H:i');

            if ($own == false || $own == null) {
                array_push($customItems, [
                    'id' => $item->id,
                    'proposer' => ApiHelper::getNameFromEmail($item->proposer),
                    'proposer_email' => $item->proposer,
                    'project_name' => $item->otherCategory == null ? '' : $item->otherCategory->name,
//                    'approver' => ApiHelper::getNameFromEmail($item->approver, false),
                    'approved' => $item->approved,
                    'approved_reason' => $item->approved_reason,
                    'amount' => count($item->details),
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ]);
            } else {
                array_push($customItems, [
                    'id' => $item->id,
                    'project_name' => $item->otherCategory == null ? '' : $item->otherCategory->name,
//                    'approver' => ApiHelper::getNameFromEmail($item->approver, false),
                    'approved' => $item->approved,
                    'approved_reason' => $item->approved_reason,
                    'amount' => count($item->details),
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ]);
            }

        }
        $data = [
            'over_times' => $customItems,
            'pagination' => [
                'total' => $paginatedData->total(),
                'per_page' => $paginatedData->perPage(),
                'current_page' => $paginatedData->currentPage(),
                'last_page' => $paginatedData->lastPage()
            ]
        ];
        return ApiHelper::responseSuccess('List Over Time', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $project = OtherCategory::whereNull('deleted_at')->find($request->input('project_category_id'));
        $user = \JWTAuth::toUser($request->token);
        $response = $this->storeValidation($request, $project, $user);
        if ($response != null) {
            return $response;
        }
        $proposer = trim($user->email);
        try {
            DB::beginTransaction();
            $overTime = new OverTime();
            $overTime->fill($request->all());
            $overTime->proposer = $proposer;
            $overTime->approved = APPROVED;
            $overTime->save();
            $overTimeDetails = $request->input('over_time_details');
            foreach ($overTimeDetails as $overTimeDetail) {
                $detail = new OverTimeDetail();
                $detail->over_time_id = $overTime->id;
                $detail->fill($overTimeDetail);
                $detail->start_datetime = date('Y-m-d H:i:s', strtotime($overTimeDetail['start_datetime']));
                $detail->end_datetime = date('Y-m-d H:i:s', strtotime($overTimeDetail['end_datetime']));
                $detail->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTraceAsString());
        }
        MailHelper::sendMailOverTime($overTime, $overTimeDetails, $project->name);
        return ApiHelper::responseSuccess('Created Over Time', ['over_time' => $overTime]);
    }

    public function storeValidation(Request $request, $project, $user)
    {
        $validator = Validator::make($request->all(), [
            'project_category_id' => 'required|integer|min:0',
            'over_time_details' => 'required|array',
            'over_time_details.*.user_id' => 'required|integer|min:0',
            'over_time_details.*.start_datetime' => 'required|date',
            'over_time_details.*.end_datetime' => 'required|date',
            'over_time_details.*.content' => 'required|min:0|max:191',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail(__('messages.validation_0'), ['error' => $validator->errors()->all()]);
        }
        if ($user == null) {
            return ApiHelper::responseClientFail(__('messages.user_exists_0'));
        }
        if ($project == null) {
            return ApiHelper::responseClientFail(__('messages.other_category_exists_0'));
        }
        $overTimeDetails = $request->input('over_time_details');
        $userIds = array();
        foreach ($overTimeDetails as $overTimeDetail) {
            array_push($userIds, $overTimeDetail['user_id']);
        }
        if (count($userIds) != count(array_unique($userIds))) {
            return ApiHelper::responseClientFail(__('messages.user_unique_0'));
        }
        return null;
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
        $overTime = OverTime::with('otherCategory', 'details', 'details.user')
            ->whereNull('deleted_at')->find($id);
        if ($overTime == null) {
            return ApiHelper::responseSuccess('OverTime Info', ['over_time' => null]);
        }
        $overTime = $overTime->toArray();
        foreach ($overTime['details'] as $key => $value) {
            $startDateTimeOt = Carbon::createFromFormat('Y-m-d H:i:s', $value['start_datetime']);
            $endDateTimeOt = Carbon::createFromFormat('Y-m-d H:i:s', $value['end_datetime']);
            $timeOt = 0;
            if ($startDateTimeOt->gt($endDateTimeOt)) {
                $overTime['details']['time'] = $timeOt;
                continue;
            };
            if (!$startDateTimeOt->diffInDays($endDateTimeOt)) {
                $timeOt = $timeOt + TimeOnCalculating::calculatingWorkingTimeOtInDay($startDateTimeOt->toTimeString(), $endDateTimeOt->toTimeString());
            } else {
                for ($dateOt = $startDateTimeOt->copy(); $dateOt->diffInDays($endDateTimeOt, false) >= 0; $dateOt->addDay()) {
                    if ($dateOt->diffInDays($startDateTimeOt) === 0) {
                        $timeOt = $timeOt + TimeOnCalculating::calculatingWorkingTimeOtInDay($startDateTimeOt->toTimeString(), '23:59:59');
                    } else {
                        if ($dateOt->diffInDays($endDateTimeOt) === 0) {
                            $timeOt = $timeOt + TimeOnCalculating::calculatingWorkingTimeOtInDay($endDateTimeOt->toTimeString(), '23:59:59');
                        } else {
                            $timeOt = $timeOt + 24 * 60 - 150;
                        }
                    }
                }
            }
            $overTime['details'][$key]['time'] = round($timeOt / 60, 1);
        }
        return ApiHelper::responseSuccess('OverTime Info', ['over_time' => $overTime]);
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
        $project = OtherCategory::whereNull('deleted_at')->find($request->input('project_category_id'));
        $user = \JWTAuth::toUser($request->token);
        $response = $this->storeValidation($request, $project, $user);
        if ($response != null) {
            return $response;
        }
        try {
            DB::beginTransaction();
            $overTime = OverTime::whereNull('deleted_at')->find($id);
            if ($overTime == null) {
                return ApiHelper::responseClientFail(__('messages.over_time_exist_0'));
            }
            $overTime->fill($request->except('proposer'));
            $overTime->approved = PENDING;
            $overTime->save();
            $overTimeDetails = $request->input('over_time_details');
            $oldDetails = $overTime->details()->whereNull('deleted_at')->get();
            foreach ($oldDetails as $oldDetail) {
                $oldDetail->delete();
            }
            foreach ($overTimeDetails as $overTimeDetail) {
                $detail = new OverTimeDetail();
                $detail->over_time_id = $overTime->id;
                $detail->fill($overTimeDetail);
                $detail->start_datetime = date('Y-m-d H:i:s', strtotime($overTimeDetail['start_datetime']));
                $detail->end_datetime = date('Y-m-d H:i:s', strtotime($overTimeDetail['end_datetime']));
                $detail->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTraceAsString());
        }
        MailHelper::sendMailOverTime($overTime, $overTimeDetails, $project->name);
        return ApiHelper::responseSuccess('Updated Over Time', ['over_time' => $overTime]);
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
            $overTime = OverTime::where('deleted_at', '=', null)
                ->where('id', '=', $id)->first();
            if ($overTime == null) {
                return ApiHelper::responseClientFail(__('messages.over_time_exist_0'));
            }
            $overTimeDetails = $overTime->details()->get();
            foreach ($overTimeDetails as $overTimeDetail) {
                $overTimeDetail->deleted_at = new \DateTime();
                $overTimeDetail->save();
            }
            $overTime->deleted_at = new \DateTime();
            $overTime->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTraceAsString());
        }
        return ApiHelper::responseSuccess('Deleted Over Time', ['over_time' => $overTime->id]);
    }

    public function getApproveOverTime(Request $request)
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
        $user = \JWTAuth::toUser($request->token);
        $email = $user->email;
        $ot = OverTime::with('otherCategory', 'details')
            ->whereNull('deleted_at')
            ->where('proposer', '<>', $email)
            ->where('approver', 'like', "%$email%");
        if ($approved != null) {
            $ot->where('approved', '=', $approved);
        }
        $paginatedData = $ot->distinct()
            ->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);
        $overTimes = array();

        foreach ($paginatedData as $overTime) {
            $created_at = $overTime->created_at == null ? '' : Carbon::parse($overTime->created_at)->format('d-m-Y H:i:s');
            array_push($overTimes, [
                'id' => $overTime->id,
                'proposer' => ApiHelper::getNameFromEmail($overTime->proposer),
                'proposer_email' => $overTime->proposer,
                'project_name' => $overTime->otherCategory == null ? '' : $overTime->otherCategory->name,
//                'approver' => ApiHelper::getNameFromEmail($overTime->approver, false),
                'approved' => $overTime->approved,
                'approved_reason' => $overTime->approved_reason,
                'amount' => count($overTime->details),
                'created_at' => $created_at,
            ]);
        }
        $data = [
            'over_times' => $overTimes,
            'pagination' => [
                'total' => $paginatedData->total(),
                'per_page' => $paginatedData->perPage(),
                'current_page' => $paginatedData->currentPage(),
                'last_page' => $paginatedData->lastPage()
            ]
        ];
        return ApiHelper::responseSuccess('List Over Time', $data);
    }

    public function approveOverTime(Request $request)
    {
        $over_time_ids = $request->input('over_time_ids');
        $approved_reason = $request->input('approved_reason');
        $validator = Validator::make($request->all(), [
            'over_time_ids' => 'required|array',
            'over_time_ids.*' => 'integer'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail(__('messages.validation_0'), ['error' => $validator->errors()->messages()]);
        }
        $over_time_ids = array_unique($over_time_ids);
        $overTimes = OverTime::query();
        $overTimes->whereIn('id', $over_time_ids);
        $overTimes = $overTimes->get();
        $approved_over_time_ids = array();
        try {
            DB::beginTransaction();
            foreach ($overTimes as $overTime) {
                $overTime->approved = APPROVED;
                $overTime->approved_reason = $approved_reason;
                $overTime->save();
                array_push($approved_over_time_ids, $overTime->id);
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseClientFail($e->getMessage(), $e->getTrace());
        }
        $approver = \JWTAuth::toUser($request->token);
        $approverName = $approver == null ? __('messages.null') : $approver->name;
        $approverEmail = $approver == null ? __('messages.null') : $approver->email;
        foreach ($overTimes as $overTime) {
            MailHelper::sendMailApproveOverTime($overTime, $overTime->details()->get(), $approverName, $approverEmail);
        }
        return ApiHelper::responseSuccess('Approved', ['over_time_ids' => $approved_over_time_ids]);
    }

    public function refuseOverTime(Request $request)
    {
        $over_time_ids = $request->input('over_time_ids');
        $approved_reason = $request->input('approved_reason');
        $validator = Validator::make($request->all(), [
            'over_time_ids' => 'required|array',
            'over_time_ids.*' => 'integer'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail(__('messages.validation_0'), ['error' => $validator->errors()->messages()]);
        }
        $over_time_ids = array_unique($over_time_ids);
        $overTimes = OverTime::query();
        $overTimes->whereIn('id', $over_time_ids);
        $overTimes = $overTimes->get();
        $refused_over_time_ids = array();
        try {
            DB::beginTransaction();

            foreach ($overTimes as $overTime) {
                $overTime->approved = REFUSED;
                $overTime->approved_reason = $approved_reason;
                $overTime->save();
                array_push($refused_over_time_ids, $overTime->id);
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseClientFail($e->getMessage(), $e->getTrace());
        }
        $approver = \JWTAuth::toUser($request->token);
        $approverName = $approver == null ? __('messages.null') : $approver->name;
        $approverEmail = $approver == null ? __('messages.null') : $approver->email;
        foreach ($overTimes as $overTime) {
            MailHelper::sendMailApproveOverTime($overTime, $overTime->details()->get(), $approverName, $approverEmail);
        }
        return ApiHelper::responseSuccess('Refused', ['over_time_ids' => $refused_over_time_ids]);
    }
}
