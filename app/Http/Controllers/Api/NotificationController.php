<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Notification;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Validator;

class NotificationController extends Controller
{
    //
    public function storeFcmDeviceToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
//            'email' => 'required|email|min:0|max:191',
            'fcm_device_token' => 'required|min:0|max:191',
            'type' => 'required|integer|min:0|max:2',
        ]);

        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validation', ['error' => $validator->errors()->all()]);
        }
        $currentUser = \JWTAuth::toUser($request->token);
        if ($currentUser == null) {
            return ApiHelper::responseClientFail(__('messages.user_exists_0'));
        }
        $user = User::where('email', '=', $currentUser->email)->get();
        if ($user == null) {
            return ApiHelper::responseClientFail(__('messages.user_exists_0'));
        }
        try {
            DB::beginTransaction();
            $device = Device::where('fcm_device_token', '=', $request->input('fcm_device_token'))->first();
            if ($device == null) {
                $device = new Device();
            }
            $device->fill($request->all());
            $device->email = $currentUser->email;
            $device->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Created device', ['device' => $device]);
    }

    public function indexFcmDeviceToken()
    {
        $devices = Device::where('deleted_at', '=', null)->get();
        return ApiHelper::responseSuccess('List Devices', ['devices' => $devices]);
    }

    public function delete($id)
    {
        $device = Device::find($id);
        if ($device == null) {
            return ApiHelper::responseClientFail(__('messages.device_exists_0'));
        }
        try {
            DB::beginTransaction();
            $device->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Deleted Devices', ['device' => $device]);
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'integer|min:0',
            'limit' => 'integer|min:0',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), ['error' => $validator->errors()->all()]);
        }
        $page = $request->input('page');
        $limit = $request->input('limit');
        $page = $page == null ? DEFAULT_DISPLAY_PAGE : $page;
        $limit = $limit == null ? DEFAULT_PAGE_LIMIT : $limit;
        $user = \JWTAuth::toUser($request->token);
        if ($user == null) {
            return ApiHelper::responseClientFail(__('messages.user_exists_0'));
        }
        $paginatedData = Notification::whereNull('deleted_at')
            ->whereHas('user', function ($query) use ($user) {
                $query->where('id', '=', $user->id);
            })
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('timeOff')->orWhereHas('overTime')->orWhereHas('course')->orWhereHas('training');
                })->orWhere(function ($q) {
                    $q->whereNull('time_off_id')->whereNull('over_time_id')->whereNull('course_id')->whereNull('training_id');
                });
            })
            ->orderBy('created_at', 'desc')
            ->distinct()
            ->paginate($limit, ['*'], 'page', $page);
        $notifications = array();
        foreach ($paginatedData as $notification) {
            $body = explode("\n", $notification->body);
            array_push($notifications, [
                'id' => $notification->id,
                'title' => $notification->title,
                'body' => $body,
                'read' => $notification->read,
                'created_at' => $notification->created_at == null ? null : $notification->created_at->toDateTimeString(),
                'type' => $notification->type,
                'time_off_id' => $notification->time_off_id,
                'over_time_id' => $notification->over_time_id,
                'course_id' => $notification->course_id,
                'training_id' => $notification->training_id,
            ]);
        }
        $data = [
            'notifications' => $notifications,
            'pagination' => [
                'total' => $paginatedData->total(),
                'per_page' => $paginatedData->perPage(),
                'current_page' => $paginatedData->currentPage(),
                'last_page' => $paginatedData->lastPage()
            ]
        ];
        return ApiHelper::responseSuccess('List Notification', $data);
    }

    public function deleteNotification($id)
    {
        try {
            DB::beginTransaction();
            $notification = Notification::find($id);
            if ($notification == null) {
                return ApiHelper::responseClientFail(__('messages.notification_exists_0'));
            }
            $notification->deleted_at = new \DateTime();
            $notification->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Deleted Notification', ['notification_id' => $notification->id]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::whereNull('deleted_at')->find($id);
        if ($notification == null) {
            return ApiHelper::responseClientFail(__('messages.notification_exists_0'));
        }
        try {
            DB::beginTransaction();
            $notification->read = !$notification->read;
            $notification->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return ApiHelper::responseSuccess('Marked as read', ['notification' => $notification]);
    }
}
