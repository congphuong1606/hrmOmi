<?php
/**
 * Created by PhpStorm.
 * User: Ominext
 * Date: 7/9/2018
 * Time: 8:50 AM
 */

namespace App\Helpers;


use App\Jobs\SendNotification;
use App\Models\Device;
use App\Models\Notification;
use DB;
use Requests;

class NotificationHelper
{

    public static function getNotificationMessageFromType($type, $body, $title)
    {
        switch ($type) {
            case DEVICES_TYPE_ANDROID:
                $android = [
                    'title' => $title,
                    'body' => $body,
                    'sound' => 'default',
                    'icon' => 'date',
                    'click_action' => ''
                ];
                return $android;
            case DEVICES_TYPE_IOS:
                $ios = [
                    'title' => $title,
                    'body' => $body,
                    'sound' => 'blip',
                    'click_action' => ''
                ];
                return $ios;
            case DEVICES_TYPE_WEB:
                $web = [
                    'title' => $title,
                    'body' => $body,
                    'icon' => HRM_ICON,
                    'click_action' => '/' . ACTION_HOME
                ];
                return $web;
        }
        return null;
    }

    public static function sendNotification($devices, $notificationBody, $notificationTitle)
    {
        \Log::info('sendNotification : ' . count($devices));
        \Log::info($devices);
        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => 'key=' . FCM_SERVER_KEY
        ];
        foreach ($devices as $token) {
            try {
                \Log::info('token: ' . $token['fcm_device_token']);
                $title = null;
                $body = null;
                $notification = self::getNotificationMessageFromType($token['type'], $notificationBody, $notificationTitle);
                $data = [
                    'to' => $token['fcm_device_token'],
                    'notification' => $notification
                ];
                \Log::info('data raw: ' . json_encode($data));
                $response = Requests::post(FCM_URL, $header, json_encode($data));
                if (!$response->success) {
                    Requests::post(FCM_URL, $header, json_encode($data));
                    \Log::error('Re-send Notification : ' . $response->body);
                }
            } catch (\Exception $e) {
                \Log::info($e->getMessage());
            }
        }
        return true;
    }


    public static function getFcmTokensFromMails($emails)
    {
        $emails = array_unique($emails);
        $fcmTokens = array();
        foreach ($emails as $email) {
            $devices = Device::where('email', '=', $email)
                ->whereNotNull('email')
                ->whereNotNull('fcm_device_token')
                ->whereNotNull('type')
                ->get();
            foreach ($devices as $device) {
                array_push($fcmTokens, [
                    'fcm_device_token' => $device->fcm_device_token,
                    'type' => $device->type,
                    'email' => $email
                ]);
            }
        }
        return $fcmTokens;
    }

    public static function saveNotification($title = '', $body = '', $action = '', $email = '', $type = 0, $source_id = null)
    {
        \Log::info('saveNotification : ' . $email);
//        $notification = Notification::where('title', 'like', $title)
//            ->where('body', 'like', $body)
//            ->where('email','=',$email)
//            ->first();
//        if ($notification) {
//            return;
//        }
        try {
            DB::beginTransaction();
            $noti = new Notification();
            $noti->title = ($title);
            $noti->body = ($body);
            $noti->action = ($action);
            $noti->email = ($email);
            switch ($type) {
                case NOTIFICATION_TYPE_DEFAULT:
                    $noti->type = NOTIFICATION_TYPE_DEFAULT;
                    break;
                case NOTIFICATION_TYPE_TIME_OFF:
                    $noti->type = NOTIFICATION_TYPE_TIME_OFF;
                    $noti->time_off_id = $source_id;
                    break;
                case NOTIFICATION_TYPE_TIME_OFF_REFUSED:
                    $noti->type = NOTIFICATION_TYPE_TIME_OFF_REFUSED;
                    $noti->time_off_id = $source_id;
                    break;
                case NOTIFICATION_TYPE_TIME_OFF_APPROVED:
                    $noti->type = NOTIFICATION_TYPE_TIME_OFF_APPROVED;
                    $noti->time_off_id = $source_id;
                    break;
                case NOTIFICATION_TYPE_OVER_TIME:
                    $noti->type = NOTIFICATION_TYPE_OVER_TIME;
                    $noti->over_time_id = $source_id;
                    break;
                case NOTIFICATION_TYPE_OVER_TIME_APPROVED:
                    $noti->type = NOTIFICATION_TYPE_OVER_TIME_APPROVED;
                    $noti->over_time_id = $source_id;
                    break;
                case NOTIFICATION_TYPE_OVER_TIME_REFUSED:
                    $noti->type = NOTIFICATION_TYPE_OVER_TIME_REFUSED;
                    $noti->over_time_id = $source_id;
                    break;
                case NOTIFICATION_TYPE_COURSE:
                    $noti->type = NOTIFICATION_TYPE_COURSE;
                    $noti->course_id = $source_id;
                    break;
                case NOTIFICATION_TYPE_TRAINING:
                    $noti->type = NOTIFICATION_TYPE_TRAINING;
                    $noti->course_id = $source_id;
                    break;
            }
            $noti->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('SAVE EXCEPTION : ' . $e->getMessage());
        }
    }

    public static function send($receivers, $notificationBody, $mailSubject, $type, $sourceId)
    {
        $notificationJob = (new SendNotification($receivers, $notificationBody, $mailSubject, $type, $sourceId))
            ->onQueue('sendNotification');
        dispatch($notificationJob);
    }
}