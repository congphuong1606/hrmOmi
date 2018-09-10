<?php
/**
 * Created by PhpStorm.
 * User: Ominext
 * Date: 5/29/2018
 * Time: 8:35 AM
 */

namespace App\Helpers;


use App\Jobs\SendEmail;
use App\Jobs\SendNotification;
use App\Models\Employee;
use App\Models\User;
use DateTime;

class MailHelper
{
    public static function sendEmailTimeOffRequest($timeOff, $type)
    {
        if (!$timeOff) {
            return ERROR_MESSAGE_SEND_EMAIL;
        }
        $employee = Employee::with('department', 'user', 'project_managers')
            ->whereHas('department')
            ->whereHas('user')
            ->whereNull('deleted_at')
            ->where('id', '=', $timeOff->employee_id)
            ->first();

        if ($employee) {
            $name = $employee->full_name;
            $department = $employee->department;
            $user = $employee->user;
            $departmentName = $department->name;
            $email = $user->email;
        } else {
            return ERROR_MESSAGE_SEND_EMAIL_VALIDATION;
        }
        $data = null;
        $view = 'mails.';

        switch ($type) {
            case TYPE_ALL_DAY:
                $notificationBody = 'Người yêu cầu: ' . $name . "\n" .
                    'Loại lý do: Nghỉ cả ngày' . "\n" .
                    'Lý do: ' . $timeOff->reason . "\n" .
                    'Lý do cụ thể: ' . $timeOff->detailed_reason;
                $view = $view . 'mail_request_allday';
                $data = [
                    'department' => $departmentName == null ? __('messages.null') : $departmentName,
                    'name' => $name == null ? __('messages.null') : $name,
                    'email' => $email == null ? __('messages.null') : $email,
                    'date' => date('d-m-Y', strtotime($timeOff->start_datetime)),
                    'reason' => $timeOff->reason,
                    'detail_reason' => $timeOff->detailed_reason,
                    'backup_person' => $timeOff->backup_person,
                    'approver' => ''
                ];
                break;
            case TYPE_MULTIPLE_DAYS:
                $notificationBody = 'Người yêu cầu: ' . $name . "\n" .
                    'Loại lý do: Nghỉ nhiều ngày' . "\n" .
                    'Lý do: ' . $timeOff->reason . "\n" .
                    'Lý do cụ thể: ' . $timeOff->detailed_reason;
                $view = $view . 'mail_request_multiple_day';
                $data = [
                    'department' => $departmentName == null ? '<...>' : $departmentName,
                    'name' => $name == null ? __('messages.null') : $name,
                    'email' => $email == null ? __('messages.null') : $email,
                    'start_date' => date('d-m-Y', strtotime($timeOff->start_datetime)),
                    'end_date' => date('d-m-Y', strtotime($timeOff->end_datetime)),
                    'reason' => $timeOff->reason,
                    'detail_reason' => $timeOff->detailed_reason,
                    'backup_person' => $timeOff->backup_person,
                    'approver' => ''
                ];
                break;
            case TYPE_DID_NOT_CHECK_OUT_CHECK_IN:
                $notificationBody = '- Người yêu cầu: ' . $name . "\n" .
                    'Loại lý do: Quên checkin/checkout' . "\n" .
                    'Lý do cụ thể: ' . $timeOff->detailed_reason;
                $view = $view . 'mail_request_check_in_check_out';
                $data = [
                    'department' => $departmentName == null ? __('messages.null') : $departmentName,
                    'name' => $name == null ? __('messages.null') : $name,
                    'email' => $email == null ? __('messages.null') : $email,
                    'date' => date('d-m-Y', strtotime($timeOff->start_datetime)),
                    'detail_reason' => $timeOff->detailed_reason,
                    'backup_person' => __('messages.null'),
                    'approver' => ''
                ];
                break;
            default:
                $notificationBody = 'Người yêu cầu: ' . $name . "\n" .
                    'Loại lý do: Đi muộn/về sớm/ra ngoài' . "\n" .
                    'Lý do: ' . $timeOff->reason . "\n" .
                    'Lý do cụ thể: ' . $timeOff->detailed_reason;
                $view = $view . 'mail_request_il_le_lo';
                $data = [
                    'department' => $departmentName == null ? __('messages.null') : $departmentName,
                    'name' => $name == null ? __('messages.null') : $name,
                    'email' => $email == null ? __('messages.null') : $email,
                    'date' => date('d-m-Y', strtotime($timeOff->start_datetime)),
                    'end_date' => date('d-m-Y', strtotime($timeOff->end_datetime)),
                    'from' => date('H:i:s', strtotime($timeOff->start_datetime)),
                    'to' => date('H:i:s', strtotime($timeOff->end_datetime)),
                    'reason' => $timeOff->reason,
                    'detail_reason' => $timeOff->detailed_reason,
                    'backup_person' => $timeOff->backup_person,
                    'approver' => ''
                ];
                break;
        }

        $mailSubject = "Yêu cầu nghỉ phép - $name ($email)";
        // TODO : set mail
        $toMail = '';
        $ccMails = array();

        $isBom = ApiHelper::isBom($employee->id);
        $isPM = ApiHelper::isPM($employee->id);
        $isTeamleader = ApiHelper::isTeamLeader($employee->id);

        if ($isBom) {
            \Log::info('$isBom');
            $toMail = $timeOff->backup_person;
        } else if ($isTeamleader) {
            \Log::info('$isTeamleader');
            $boms = Employee::whereNull('deleted_at')
                ->whereHas('user', function ($query) {
                    $query->whereHas('roles', function ($query) {
                        $query->where('name', 'like', SPECIFIC_ROLE_BOM);
                    });
                })->get();
            $bomEmails = array();
            foreach ($boms as $bom) {
                \Log::info("SEND:" . $bom->email);
                array_push($bomEmails, $bom->email);
                $data['approver'] = ApiHelper::getNameFromEmail($bom->email);
                self::send($bom->email, $ccMails, $data, $view, $mailSubject);
            }
            \Log::info('Notification for BOM : ' . json_encode($bomEmails));
            \Log::info('Email for Backup person : ' . $timeOff->backup_person);
            self::send($timeOff->backup_person, $ccMails, $data, $view, $mailSubject);
            NotificationHelper::send($bomEmails, $notificationBody, $mailSubject, NOTIFICATION_TYPE_TIME_OFF, $timeOff->id);
            return true;
        } else if ($isPM) {
            \Log::info('$isPM');
            $teamLeaders = Employee::whereNull('deleted_at')
                ->where('department_id', '=', $employee->department_id)
                ->whereHas('user', function ($query) {
                    $query->whereHas('roles', function ($query) {
                        $query->where('name', 'like', SPECIFIC_ROLE_TEAM_LEADER);
                    });
                })->get();
            $teamLeaderMails = array();
            foreach ($teamLeaders as $teamLeader) {
                \Log::info($teamLeader->email);
                array_push($teamLeaderMails, $teamLeader->email);
                $data['approver'] = ApiHelper::getNameFromEmail($teamLeader->email);
                self::send($teamLeader->email, $ccMails, $data, $view, $mailSubject);
            }
            self::send($timeOff->backup_person, $ccMails, $data, $view, $mailSubject);
            NotificationHelper::send($teamLeaderMails, $notificationBody, $mailSubject, NOTIFICATION_TYPE_TIME_OFF, $timeOff->id);
            return true;
        } else {
            \Log::info('$others');
            $teamLeader = User::whereHas('employee', function ($query) use ($employee) {
                $query->where('department_id', '=', $employee->department_id);
            })->whereHas('roles', function ($query) {
                $query->where('name', 'like', SPECIFIC_ROLE_TEAM_LEADER);
            })->first();
            if ($employee->project_managers == null) {
                if ($teamLeader != null) {
                    $toMail = $teamLeader->email;
                }
            } else {
                $project_manager = $employee->project_managers[0];
                $toMail = $project_manager->email;
                if ($teamLeader != null) {
                    array_push($ccMails, $teamLeader->email);
                }
            }

        }
        \Log::info('toMail: ' . $toMail);
        \Log::info('ccMails: ' . json_encode($ccMails));
        NotificationHelper::send([$toMail], $notificationBody, $mailSubject, NOTIFICATION_TYPE_TIME_OFF, $timeOff->id);
        $data['approver'] = ApiHelper::getNameFromEmail($toMail);
        array_push($ccMails, $timeOff->backup_person);
        self::send($toMail, $ccMails, $data, $view, $mailSubject);
        return true;
    }

    public static function send($toMail, $ccMails, $data, $view, $mailSubject)
    {
        $mailJob = (new SendEmail($toMail, $ccMails, $data, $view, $mailSubject))->onQueue('sendMail');
        dispatch($mailJob);
    }

    public static function sendMailOverTime($overTime, $overTimeDetails, $projectName)
    {
        if ($overTime == null || $overTimeDetails == null) {
            return null;
        }
        $proposer = $overTime->proposer;
        $proposedUser = User::with('employee')
            ->whereHas('employee', function ($query) {
                $query->whereHas('department');
            })
            ->where('email', '=', $proposer)
            ->first();
        $department = $proposedUser == null ? __('messages.null') : $proposedUser->employee->department->name;
        // TODO: set mail
        $toMail = $proposer;
        $ccMails = array();
        $detailContents = array();
        $count = 0;
        foreach ($overTimeDetails as $overTimeDetail) {
            if ($overTimeDetail != null) {
                $overTimeUser = User::find($overTimeDetail['user_id']);
                $count++;
                array_push($detailContents, [
                    'order' => $count,
                    'name' => $overTimeUser == null ? __('messages.null') : ApiHelper::getNameFromEmail($overTimeUser->email),
                    'email' => $overTimeUser == null ? __('messages.null') : $overTimeUser->email,
                    'content' => $overTimeDetail == null ? __('messages.null') : $overTimeDetail['content'],
                    'date' => $overTimeDetail == null ? __('messages.null') : date('d-m-Y', strtotime($overTimeDetail['start_datetime'])),
                    'from' => $overTimeDetail == null ? __('messages.null') : date('H:i', strtotime($overTimeDetail['start_datetime'])),
                    'to' => $overTimeDetail == null ? __('messages.null') : date('H:i', strtotime($overTimeDetail['end_datetime'])),
                ]);
                $email = $overTimeUser == null ? null : $overTimeUser->email;
                if ($email != null) {
                    array_push($ccMails, $email);
                }
            }
        }
        $proposerName = ApiHelper::getNameFromEmail($proposer);
        $data = [
            'name' => $proposerName,
            'email' => $proposer,
            'project' => $projectName,
            'department' => $department,
            'created_at' => date('d-m-Y', strtotime($overTime->created_at)),
            'number_of_person' => $count,
            'detail_contents' => $detailContents
        ];
        $mailSubject = "Đăng ký OT - $proposerName ($proposer)";
        $view = 'mails.mail_request_over_time';
        $job = (new SendEmail(
            $toMail,
            $ccMails,
            $data,
            $view,
            $mailSubject
        ))->onQueue('sendMail');
        dispatch($job);
    }

    public static function sendMailApproveTimeOff($timeOff, $approverName)
    {
        if (!$timeOff) {
            return ERROR_MESSAGE_SEND_EMAIL;
        }
        $employee = Employee::with('department', 'user')
            ->whereNull('deleted_at')
            ->whereHas('user')
            ->where('id', '=', $timeOff->employee_id)
            ->first();

        if ($employee) {
            $name = $employee->full_name;
            $email = $employee->email;
        } else {
            return ERROR_MESSAGE_SEND_EMAIL_VALIDATION;
        }
        $data = null;
        $view = 'mails.mail_approve_time_off';
        $start_datetime = $timeOff->start_datetime;
        $end_datetime = $timeOff->end_datetime;
        $detailed_reason = $timeOff->detailed_reason;
        $approved_reason = $timeOff->approved_reason;
        $approved = $timeOff->approved;
        $data = [
            'name' => $name == null ? __('messages.null') : $name,
            'email' => $email == null ? __('messages.null') : $email,
            'start_datetime' => $start_datetime == null ? __('messages.null') : date('H:i d-m-Y', strtotime($start_datetime)),
            'end_datetime' => $end_datetime == null ? __('messages.null') : date('H:i d-m-Y', strtotime($end_datetime)),
            'detailed_reason' => $detailed_reason == null ? __('messages.null') : $detailed_reason,
            'approver' => $approverName == null ? __('messages.null') : $approverName,
            'approved_reason' => $approved_reason == null ? __('messages.null') : $approved_reason,
            'approved' => $approved,
        ];
        \Log::info($data['email']);
        // TODO : set mail
        $toMail = $email;
        $ccMails = array();
        $mailSubject = null;
        $status = null;
        if ($approved == APPROVED) {
            $status = 'Đã được duyệt';
            $mailSubject = "Phê duyệt yêu cầu nghỉ phép - $name ($email)";
        } else if ($approved = REFUSED) {
            $status = 'Bị từ chối';
            $mailSubject = "Từ chối yêu cầu nghỉ phép - $name ($email)";
        }
        $now = (new DateTime())->format('H:i:s d-m-Y');
        $notificationBody = 'Người duyệt: ' . $approverName . "\n" .
            'Tin nhắn: ' . $timeOff->approved_reason . "\n" .
            'Thời gian duyệt: ' . $now . "\n" .
            'Trạng thái: ' . $status;

        $notificationType = $approved == APPROVED ? NOTIFICATION_TYPE_TIME_OFF_APPROVED : NOTIFICATION_TYPE_TIME_OFF_REFUSED;
        // Send notification
        $notificationJob = (new SendNotification(
            [$toMail],
            $notificationBody,
            $mailSubject,
            $notificationType,
            $timeOff->id
        ))->onQueue('sendNotification');
        dispatch($notificationJob);
        // Dispatch job
        $job = (new SendEmail(
            $toMail,
            $ccMails,
            $data,
            $view,
            $mailSubject
        ))->onQueue('sendMail');
        dispatch($job);
        return true;
    }

    public static function sendMailApproveOverTime($overTime, $overTimeDetails, $approverName, $approverEmail)
    {
        $proposer = $overTime == null ? null : $overTime->proposer;
        $proposedUser = User::with('employee')->whereHas('employee', function ($query) {
            $query->whereHas('department');
        })->where('email', '=', $proposer)->first();
        $department = $proposedUser == null ? __('messages.null') : $proposedUser->employee->department->name;
        $detailContents = array();
        $count = 0;
        foreach ($overTimeDetails as $overTimeDetail) {
            if ($overTimeDetail != null) {
                $overTimeUser = User::find($overTimeDetail['user_id']);
                $count++;
                array_push($detailContents, [
                    'order' => $count,
                    'name' => $overTimeUser == null ? __('messages.null') : ApiHelper::getNameFromEmail($overTimeUser->email),
                    'email' => $overTimeUser == null ? __('messages.null') : $overTimeUser->email,
                    'content' => $overTimeDetail == null ? __('messages.null') : $overTimeDetail['content'],
                    'date' => $overTimeDetail == null ? __('messages.null') : date('d-m-Y', strtotime($overTimeDetail['start_datetime'])),
                    'from' => $overTimeDetail == null ? __('messages.null') : date('H:i', strtotime($overTimeDetail['start_datetime'])),
                    'to' => $overTimeDetail == null ? __('messages.null') : date('H:i', strtotime($overTimeDetail['end_datetime'])),
                ]);
            }
        }
        $proposerName = ApiHelper::getNameFromEmail($proposer);
        $data = [
            'name' => $proposerName,
            'approver' => $approverName,
            'email' => $proposer,
            'department' => $department,
            'created_at' => date('d-m-Y', strtotime($overTime->created_at)),
            'number_of_person' => $count,
            'detail_contents' => $detailContents,
            'approved' => $overTime->approved,
            'approved_reason' => $overTime->approved_reason
        ];
        $approved = $overTime->approved;
        $mailSubject = null;
        $status = null;
        if ($approved == APPROVED) {
            $status = 'Đã được duyệt';
            $mailSubject = "Phê duyệt yêu cầu làm thêm giờ - $approverName ($approverEmail)";
        } else if ($approved = REFUSED) {
            $status = 'Bị từ chối';
            $mailSubject = "Từ chối yêu cầu làm thêm giờ - $approverName ($approverEmail)";
        }
        $now = (new DateTime())->format('H:i:s d-m-Y');
        $notificationBody = 'Người duyệt: ' . $approverName . "\n" .
            'Tin nhắn: ' . $overTime->approved_reason . "\n" .
            'Thời gian duyệt: ' . $now . "\n" .
            'Trạng thái: ' . $status;
        // TODO : set mail
        $toMail = $proposer;
        $ccMails = array();
        $view = 'mails.mail_approve_overtime';

        // Send notification
        $notificationType = $approved == APPROVED ? NOTIFICATION_TYPE_OVER_TIME_APPROVED : NOTIFICATION_TYPE_OVER_TIME_REFUSED;
        $notificationJob = (new SendNotification(
            [$toMail],
            $notificationBody,
            $mailSubject,
            $notificationType,
            $overTime->id
        ))->onQueue('sendNotification');
        dispatch($notificationJob);
        // Dispatch job
        $job = (new SendEmail(
            $toMail,
            $ccMails,
            $data,
            $view,
            $mailSubject
        ))->onQueue('sendMail');
        dispatch($job);
    }

    public static function sendMailScore($sender, $courseName, $score, $receiver, $department, $courseId)
    {
        $now = new DateTime();
        $timeStamp = strtotime('+4 days', strtotime($now->format('d-m-Y')));
        $responseDay = date('d', $timeStamp);
        $responseMonth = date('m', $timeStamp);
        $responseYear = date('Y', $timeStamp);
        $data = [
            'receiver_name' => ApiHelper::getNameFromEmail($receiver),
            'course_name' => $courseName,
            'department' => $department,
            'score' => $score,
            'response_day' => $responseDay,
            'response_month' => $responseMonth,
            'response_year' => $responseYear,
            'sender' => $sender == null ? __('messages.null') : $sender->full_name,
        ];
        $view = 'mails.mail_course_training_score';
        $mailSubject = 'THÔNG BÁO ĐIỂM BÀI TEST ' . $courseName;
        $notificationBody = 'Điểm khóa học ' . $courseName . ': ' . $score;
        // Send notification
        // TODO : set mail
        $toMail = $receiver;
        $ccMails = array();

        $notificationJob = (new SendNotification(
            [$toMail],
            $notificationBody,
            $mailSubject,
            NOTIFICATION_TYPE_TRAINING,
            $courseId
        ))->onQueue('sendNotification');
        dispatch($notificationJob);
        // Dispatch job
        $job = (new SendEmail(
            $toMail,
            $ccMails,
            $data,
            $view,
            $mailSubject
        ))->onQueue('sendMail');
        dispatch($job);
    }

    public static function sendMailForgetPassword($email, $verified_code)
    {
        $view = 'mails.mail_password_reset';
        $data = [
            'verified_code' => $verified_code
        ];
        $mailSubject = '[OMI_HRM]Password reset';
        // TODO : set mail
        $toMail = $email;
        $ccMails = array();
        $job = (new SendEmail(
            $toMail,
            $ccMails,
            $data,
            $view,
            $mailSubject
        ))->onQueue('sendMail');
        dispatch($job);
    }
}