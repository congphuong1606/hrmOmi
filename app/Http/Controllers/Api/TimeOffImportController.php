<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Setting;
use App\Models\TimeOff;
use App\Models\TimeOffFile;
use DateTime;
use DB;
use Excel;
use Illuminate\Http\Request;
use Validator;

class TimeOffImportController extends Controller
{
    //
    public function importTimeoff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), $validator->errors()->all());
        }
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $mimeType = $file->getClientMimeType();
        if (!in_array($mimeType, ApiHelper::$excelMIME)) {
            return ApiHelper::responseClientFail(__('messages.file_invalid'));
        }
        $setting = Setting::first();
        if ($setting == null) {
            return 'setting';
        }
        $user = \JWTAuth::toUser($request->token);
        if ($user == null) {
            return 'user';
        }
        $userId = $user == null ? null : $user->id;
        $savingName = $this->getCourseScoreExcelFileName($file);
        $file->storeAs('timeoff/', $savingName);
        $results = array();
        Excel::load('storage/app/timeoff/' . $savingName, function ($reader) use ($userId, $fileName, $setting, &$results) {
            $reader->limitColumns(25);
            $reader->skipRows(1);
            $reader->skipColumns(2);
            // email : 0
            // pm : 3
            // team lead : 4
            // status : 5
            // Nghỉ 1 ngày : ngày nghỉ:6-lý do:7-rõ lý do:8-người thay thế:9
            // Đi muộn về sớm ra ngoài: ngày nghỉ:15-từ lúc:16-đến lúc:17-lý do:18-rõ lý do:19-người thay thế:20
            // Nghỉ nhiều ngày :từ ngày:10-đến ngàu:11-lý do:12-rõ lý do:13-người thay thế:14
            // Không check ... : ngày:21-lý do:22
            $reader->each(function ($row) use ($setting, &$results) {
                $data = $this->getDataBasedStatus($row, $setting);
                if ($data != null) {
                    array_push($results, $data);
                }
            });

            try {
                DB::beginTransaction();
                $timeOffFile = new TimeOffFile();
                $timeOffFile->name = $fileName;
                $timeOffFile->user_id = $userId;
                $timeOffFile->save();
                $timeOffFileId = $timeOffFile->id;
                foreach ($results as $result) {
                    $timeOff = new TimeOff();
                    $timeOff->fill($result);
                    $timeOff->file_id = $timeOffFileId;
                    $timeOff->save();
                }
                DB::commit();
            } catch (\Exception $e) {
                \Log::info($e->getMessage().$e->getTraceAsString());
                DB::rollBack();
            }
        });
        return ApiHelper::responseSuccess('Imported Time Off', ['number_of_record' => $results]);
    }

    public function getListFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'integer|min:0',
            'page' => 'integer|min:0'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), ['error' => $validator->errors()->all()]);
        }
        $limit = $request->input('limit');
        $page = $request->input('page');
        $limit = $limit == null ? DEFAULT_PAGE_LIMIT : $limit;
        $page = $page == null ? DEFAULT_DISPLAY_PAGE : $page;

        $paginatedData = TimeOffFile::whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);

        $data = ['time_off_excel_files' => $paginatedData->items()
            , 'pagination' => ['total' => $paginatedData->total()
                , 'per_page' => $paginatedData->perPage()
                , 'current_page' => $paginatedData->currentPage()
                , 'last_page' => $paginatedData->lastPage()]
        ];
        return ApiHelper::responseSuccess('List Time Off Excel File',  $data);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $timeOffExcelFile = TimeOffFile::find($id);
            $timeOffExcelFile->deleted_at = new DateTime();
            $timeOffExcelFile->save();
            TimeOff::whereNull('deleted_at')->where('file_id', '=', $id)->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return ApiHelper::responseSuccess('Deleted Time Off Excel File', ['time_off_excel_file_id' => $timeOffExcelFile->id]);
    }

    public function getStatusFromTime($startTime, $endTime, $setting)
    {
        $startMorning = $setting->start_morning;
        $end_afternoon = $setting->end_afternoon;
        if ($startTime == $startMorning) {
            return TYPE_IN_LATE;
        } else if ($endTime == $end_afternoon) {
            return TYPE_LEAVE_EARLY;
        } else {
            return TYPE_LEAVE_OUT;
        }
    }

    public function getDataBasedStatus($row, $setting)
    {
        $tmpRow = array_unique($row->toArray());
        if (is_null($tmpRow[0]) && count($tmpRow) == 1) {
            return null;
        }
        $rowData = array();
        $email = $row[0];
        $employee = Employee::whereNull('deleted_at')->whereHas('user', function ($query) use ($email) {
            $query->where('email', '=', trim($email));
        })->first();
        $employeeId = $employee == null ? null : $employee->id;
        if ($employeeId == null) {
            \Log::info('employeeId');
        }
        $projectManager = $row[3];
        $teamLeader = $row[4];
        $statusType = $row[5];
        $data = null;
        switch ($statusType) {
            case TIME_OFF_STATUS_TYPE_123:
                $date = $row[15] == null ? null : $row[15]->format('Y-m-d');
                $from = $row[16] == null ? null : $row[16]->format('H:i:s');
                $to = $row[17] == null ? null : $row[17]->format('H:i:s');
                if ($date == null || $from == null || $to == null) {
                    \Log::info(TIME_OFF_STATUS_TYPE_123);
                    return null;
                }
                $reason = $row[18];
                $detailedReason = $row[19];
                $backupPerson = $row[20];
                $data = [
                    'employee_id' => $employeeId,
                    'start_datetime' => $date . ' ' . $from,
                    'end_datetime' => $date . ' ' . $to,
                    'status' => $this->getStatusFromTime($from, $to, $setting),
                    'reason' => $reason,
                    'detailed_reason' => $detailedReason,
                    'project_manger' => $projectManager,
                    'team_leader' => $teamLeader,
                    'backup_person' => $backupPerson,
                ];

                break;
            case TIME_OFF_STATUS_TYPE_4:
                $date = $row[21] == null ? null : $row[21]->format('Y-m-d H:i:s');
                $detailedReason = 'did not check in/out - ' . trim($row[22]);
                if ($date == null) {
                    \Log::info(TIME_OFF_STATUS_TYPE_4);
                    return null;
                }
                array_push($rowData, $date);
                array_push($rowData, $detailedReason);

                $data = [
                    'employee_id' => $employeeId,
                    'start_datetime' => $date,
                    'end_datetime' => $date,
                    'status' => TYPE_DID_NOT_CHECK_OUT_CHECK_IN,
//                            'reason' => $reason,
                    'detailed_reason' => $detailedReason,
                    'project_manger' => $projectManager,
                    'team_leader' => $teamLeader,
//                            'backup_person' => $backupPerson,
                ];
                break;
            case TIME_OFF_STATUS_TYPE_5:
                $date = $row[6] == null ? null : $row[6]->format('Y-m-d H:i:s');
                if ($date == null) {
                    \Log::info(TIME_OFF_STATUS_TYPE_5);
                    return null;
                }
                $reason = $row[7];
                $detailedReason = $row[8];
                $backupPerson = $row[9];
                $data = [
                    'employee_id' => $employeeId,
                    'start_datetime' => $date,
                    'end_datetime' => $date,
                    'status' => TYPE_ALL_DAY,
                    'reason' => $reason,
                    'detailed_reason' => $detailedReason,
                    'project_manger' => $projectManager,
                    'team_leader' => $teamLeader,
                    'backup_person' => $backupPerson,
                ];
                break;
            case TIME_OFF_STATUS_TYPE_6:
                $fromDate = $row[10] == null ? null : $row[10]->format('Y-m-d');
                $toDate = $row[11] == null ? null : $row[11]->format('Y-m-d');
                if ($fromDate == null || $toDate == null) {
                    \Log::info(TIME_OFF_STATUS_TYPE_6);
                    return null;
                }
                $reason = $row[12];
                $detailedReason = $row[13];
                $backupPerson = $row[14];
                $data = [
                    'employee_id' => $employeeId,
                    'start_datetime' => $fromDate . ' ' . $setting->start_morning,
                    'end_datetime' => $toDate . ' ' . $setting->end_afternoon,
                    'status' => TYPE_MULTIPLE_DAYS,
                    'reason' => $reason,
                    'detailed_reason' => $detailedReason,
                    'project_manger' => $projectManager,
                    'team_leader' => $teamLeader,
                    'backup_person' => $backupPerson,
                ];
                break;
        }
        $validator = Validator::make($data, [
            'employee_id' => 'required|integer',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date',
            'detailed_reason' => 'required|min:0|max:191',
            'backup_person' => 'email|min:0|max:191',
            'project_manger' => 'required|email|min:0|max:191',
            'team_leader' => 'required|email|min:0|max:191',
            'status' => 'required|integer|min:1|max:6',
            'approved' => 'integer|min:0|max:2',
            'approved_reason' => 'min:0|max:191',
            'reason' => 'min:0|max:191',
            'file_id' => 'integer|min:0|max:191',
        ]);
        if ($validator->passes()) {
            return $data;
        } else {
            return null;
        }
    }
}
