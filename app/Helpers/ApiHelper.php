<?php
/**
 * Created by PhpStorm.
 * User: DatPA
 * Date: 2/5/2018
 * Time: 3:21 PM
 */

namespace App\Helpers;


use App\Jobs\Mailer;
use App\Jobs\ReplyEmail;
use App\Models\Employee;
use App\Models\OfficialHoliday;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Contracts\Logging\Log;

class ApiHelper
{
//    public static $startMorning = '08:00:00';
//    public static $endMorning = '11:30:00';
//    public static $startAfternoon = '13:00:00';
//    public static $endAfternoon = '17:30:00';
    public static $excelMIME = [
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    /**
     * Response Api Generate
     *
     * @param $status
     * @param $statusCode
     * @param $message
     * @param $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private static function responseApi($status, $statusCode, $message, $data = [])
    {
        return response()
            ->json(array_merge([
                'status' => $status,
                'message' => $message
            ], $data), $statusCode, [
                'Content-Type' => 'application/json'
            ]);
    }

    /**
     * Response when success
     *
     * @param string $message
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseSuccess($message = '', $data = [])
    {
        return self::responseApi('success', 200, $message, $data);
    }

    /**
     * Response when fail
     *
     * @param string $message
     * @param array $error
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseFail($message = '', $error = [], $status_code = 500)
    {
        return self::responseApi('fail', $status_code, $message, ['error' => $error]);
    }

    /**
     * Response when fail
     *
     * @param string $message
     * @param array $error
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseClientFail($message = '', $error = [])
    {
        return self::responseApi('fail', 400, $message, $error);
    }

    /**
     * generate Random String
     *
     * @param int $length
     * @return \Illuminate\Http\JsonResponse
     */
    public static function generateRandomString($length = 10)
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    }

    public static function addTime($time, $range, $format = 'H:i:s')
    {
        return date($format, strtotime("+$range minutes", strtotime($time)));
    }

    public static function timeRangeComputation($start, $end, $status, $employee_id = null)
    {
        \Log::info('timeRangeComputation... : ' . $status);
        $invalidMessage = __('messages.null');
        $startTime = strtotime($start);
        $endTime = strtotime($end);
        $startDate = date('Y-m-d', $startTime);
        $endDate = date('Y-m-d', $endTime);
        if ($endTime < $startTime) {
            return $invalidMessage;
        }
        $setting = Setting::first();
        if ($setting == null) {
            return $invalidMessage;
        }
        $startMorning_ = $setting->start_morning;
        $endMorning_ = $setting->end_morning;
        $startAfternoon_ = $setting->start_afternoon;
        $endAfternoon_ = $setting->end_afternoon;

//        $employee = Employee::whereNull('deleted_at')
//            ->whereHas('late_reasons')
//            ->with(['late_reasons' => function ($query) use ($startDate) {
//                $query->wherePivot('start_date', '<=', $startDate)->wherePivot('end_date', '=', null)->orWherePivot('end_date', '>=', $startDate);
//            }])
//            ->find($employee_id);
//        \Log::info("Time range: " . json_encode($employee));
//        if ($employee == null) {
//            $startMorning_ = $setting->start_morning;
//            $endMorning_ = $setting->end_morning;
//            $startAfternoon_ = $setting->start_afternoon;
//            $endAfternoon_ = $setting->end_afternoon;
//
//        } else {
//            if (count($employee->late_reasons)>0) {
//                $lateReason = $employee->late_reasons[0];
////            \Log::info("Time range: ".json_encode($lateReason));
//                $startMorning_ = $lateReason->start_morning;
//                $endMorning_ = $lateReason->end_morning;
//                $startAfternoon_ = $lateReason->start_afternoon;
//                $endAfternoon_ = $lateReason->end_afternoon;
//            }else{
//                $startMorning_ = $setting->start_morning;
//                $endMorning_ = $setting->end_morning;
//                $startAfternoon_ = $setting->start_afternoon;
//                $endAfternoon_ = $setting->end_afternoon;
//            }
//        }
//        \Log::info(json_encode($startMorning_));
//        \Log::info(json_encode($endMorning_));
//        \Log::info(json_encode($startAfternoon_));
//        \Log::info(json_encode($endAfternoon_));
        $startMorning = strtotime($startDate . " " . $startMorning_); // office hours  start date
        $endMorning = strtotime($startDate . " " . $endMorning_); // office hours start date
        $startAfternoon = strtotime($startDate . " " . $startAfternoon_); //office hours start date
        $endAfternoon = strtotime($startDate . " " . $endAfternoon_); // office hours start date
        $totalSeconds = 0;
        switch ($status) {
            case TYPE_IN_LATE:
//                if ($startTime != $startMorning || $endTime > $endAfternoon) {
//                    return $invalidMessage;
//                }
                if ($endTime > $endAfternoon) {
                    return $invalidMessage;
                }

                $totalSeconds += $endTime > $endMorning ? ($endMorning - $startTime) : ($endTime - $startTime);
                $totalSeconds += $endTime > $startAfternoon ? ($endTime - $startAfternoon) : 0;
                break;
            case TYPE_LEAVE_EARLY:
//                if ($endTime != $endAfternoon || $startTime < $startMorning) {
//                    return $invalidMessage;
//                }
                if ($startTime < $startMorning) {
                    return $invalidMessage;
                }
                $totalSeconds += $startTime < $startAfternoon ? ($endTime - $startAfternoon) : ($endTime - $startTime);
                $totalSeconds += $startTime < $endMorning ? ($endMorning - $startTime) : 0;
                break;
            case TYPE_LEAVE_OUT:
                if ($startTime < $startMorning || $endTime > $endAfternoon) {
                    return $invalidMessage;
                }
                if (($startTime > $endMorning && $startTime < $startAfternoon) && ($endTime > $endMorning && $endTime < $startAfternoon)) {
                    return $invalidMessage;
                }

                if ($startTime < $endMorning) {
                    $totalSeconds += $endTime < $endMorning ? ($endTime - $startTime) : ($endMorning - $startTime);
                    $totalSeconds += $endTime > $startAfternoon ? ($endTime - $startAfternoon) : 0;
                } else {
                    $totalSeconds += $startTime < $startAfternoon ? ($endTime - $startAfternoon) : ($endTime - $startTime);
                }
                break;
            case TYPE_DID_NOT_CHECK_OUT_CHECK_IN:
                $totalSeconds = 0;
                break;
            case TYPE_ALL_DAY:
//                $totalSeconds = ($endMorning - $startMorning) + ($endAfternoon - $startAfternoon);
                $totalSeconds = 28800;
                break;
            case TYPE_MULTIPLE_DAYS:
                $from = new \DateTime(date('Y-m-d', strtotime($startDate)));
                $to = new \DateTime(date('Y-m-d', strtotime($endDate)));
                \Log::info('TYPE_MULTIPLE_DAYS: start: ' . $from->format('Y-m-d') . ' - end: ' . $to->format('Y-m-d'));
                for ($i = $from; $i <= $to; $i->modify('+1 day')) {
                    $dayOfWeek = $i->format('N');
                    $date = $i->format('Y-m-d');
                    $officialHolidays = OfficialHoliday::whereDate('start_date', '=', $date)
                        ->orWhereDate('end_date', '=', $date)
                        ->orWhere(function ($query) use ($date) {
                            $query->whereDate('start_date', '>', $date)
                                ->whereDate('end_date', '<', $date);
                        })->first();
                    if ($dayOfWeek != 6 && $dayOfWeek != 7 && $officialHolidays == null) {
                        $totalSeconds += 28800;
                    } else {
                        \Log::info("Weekend time: " . $i->format('y-m-D H:i:s'));
                    }
                }

                break;
        }
        if ($status <= 5) {
            $time = (int)(($totalSeconds / 3600) * 10) / 10 . ' giờ';
        } else {
            $numberOfDays = (int)($totalSeconds / 28800);
            $secondsLeft = $totalSeconds % 28800;
            $numberOfHours = (int)(($secondsLeft / 3600) * 10) / 10;
            if ($numberOfHours !== 0) {
                if ($numberOfDays == 0) {
                    $time = $numberOfHours . ' giờ';
                } else {
                    $time = $numberOfDays . ' ngày ' . $numberOfHours . ' giờ';
                }
            } else {
                $time = $numberOfDays . ' ngày ';
            }
        }
        return $time;
//        return $totalSeconds;
    }

    public static function timeEquals($t1, $t2)
    {
//        \Log::info("compare " . $t1 . " : " . $t2);
        return strtotime($t1) === strtotime($t2);
    }

    /**
     * @param $email string
     * @param bool $type true:single; false:multiple
     * @return mixed|string
     */
    public static function getNameFromEmail($email, $type = true)
    {
        // true -> single - ex: 'vietnq@ominext.com'
        // false -> multiple - ex:  'vietnq@ominext.com; admin2@ominext.com'
        if ($email == null) {
            return null;
        }
        if ($type) {
            $employee = Employee::whereNull('deleted_at')
                ->whereHas('user', function ($query) use ($email) {
                    $query->where('email', '=', trim($email));
                })->first();

            return $employee == null ? null : $employee->full_name;
        } else {
            $emails = explode(';', $email);
            if (!empty($arrMail)) {
                return null;
            }
            $trimedEmail = array();
            foreach ($emails as $email) {
                array_push($trimedEmail, trim($email));
            }
            $employees = Employee::whereNull('deleted_at')
                ->whereHas('user', function ($query) use ($trimedEmail) {
                    $query->whereIn('email', $trimedEmail);
                })->get();
            $name = null;
            foreach ($employees as $employee) {
                if ($name == null) {
                    $name = $employee->full_name;
                } else {
                    $name = $name . ', ' . $employee->full_name;
                }
            }
            return $name == null ? null : $name;
        }
    }

    public static function checkPermission($url, $userId)
    {
        $user = User::whereHas('employee', function ($query) {
            $query->whereNull('deleted_at');
        })->find($userId);
        if ($user == null) {
            return ApiHelper::responseClientFail(__('messages.user_exists_0'));
        }
        $roles = $user->roles()->get();
        foreach ($roles as $role) {
            $screens = $role->screens()->whereNull('deleted_at')->get();
            foreach ($screens as $screen) {
                if ($screen->url == $url) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function isBom($employeeId)
    {
        $bom = Employee::whereNull('deleted_at')
            ->where('id', '=', $employeeId)
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', function ($query) {
                    $query->where('name', 'like', SPECIFIC_ROLE_BOM);
                });
            })->first();
        if ($bom != null) {
            return true;
        }
        return false;
    }

    public static function isTeamLeader($employeeId)
    {
        $teamLeader = Employee::whereNull('deleted_at')
            ->where('id', '=', $employeeId)
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', function ($query) {
                    $query->where('name', 'like', SPECIFIC_ROLE_TEAM_LEADER);
                });
            })->first();
        if ($teamLeader != null) {
            return true;
        }
        return false;
    }

    public static function isPM($employeeId)
    {
        $pm = Employee::whereNull('deleted_at')
            ->where('id', '=', $employeeId)
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', function ($query) {
                    $query->where('name', 'like', SPECIFIC_ROLE_PROJECT_MANAGER);
                });
            })->first();
        if ($pm != null) {
            return true;
        }
        return false;
    }

    public static function getTeamLeader($departmentId)
    {
        $teamLeader = Employee::whereNull('deleted_at')->whereHas('user', function ($query) {
            $query->whereHas('roles', function ($query) {
                $query->where('name', 'like', SPECIFIC_ROLE_TEAM_LEADER);
            });
        })->where('department_id', '=', $departmentId)->first();
        return $teamLeader;
    }

    public static function getProjectManager($employeeId)
    {

    }

    public static function getBoms()
    {
        $boms = Employee::whereNull('deleted_at')->whereHas('user', function ($query) {
            $query->whereHas('roles', function ($query) {
                $query->where('name', 'like', SPECIFIC_ROLE_BOM);
            });
        })->get();
        return $boms;
    }
}