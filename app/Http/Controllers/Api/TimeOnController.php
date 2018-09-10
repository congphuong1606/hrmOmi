<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeExcelData;
use App\Models\EmployeeExcelDepartment;
use App\Models\EmployeeExcelFile;
use App\Models\EmployeeExcelJobStatus;
use App\Models\EmployeeExcelPosition;
use App\Models\EmployeesAttachFiles;
use App\Models\EmployeesSpecializedSkills;
use App\Models\EmployeeUpdateHistory;
use App\Models\JobStatus;
use App\Models\JobStatusUpdateHistory;
use App\Models\OverTime;
use App\Models\Position;
use App\Models\TimeOff;
use App\Models\TimeOn;
use App\Models\TimeOnAccumulatedYear;
use App\Models\TimeOnAdditionDayOff;
use App\Models\TimeOnExcelData;
use App\Models\TimeOnExcelFile;
use App\Models\TimeOnMonth;
use App\Models\TimeOnOverTime;
use App\Models\TimeOnTimeOff;
use App\Models\User;
use App\Models\WorkingStatus;
use App\Services\TimeOnCalculating;
use Auth;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use PHPExcel_Cell;
use PHPExcel_Style_Border;
use Validator;

set_time_limit(0);

class TimeOnController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required',
            'department_id' => 'required',
        ], [
            'month.required' => 'month không được bỏ trống.',
            'department_id.required' => 'department_id không được bỏ trống.',
        ]);

        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validate', [
                'error' => $validator->errors()->toArray()
            ]);
        }
        $employee_code = $request->input('employee_code');
        $employee_name = $request->input('employee_name');
        $month = $request->input('month');
        $month = Carbon::createFromFormat('d-n-Y', '01-' . $month);
        $currentMonth = $month->format('n');
        $currentYear = $month->format('Y');
        $department_id = $request->input('department_id');
        $department_id = (int)$department_id;
        $job_status_id = $request->input('job_status_id');
        $job_status_id = (int)$job_status_id;
        $daysInMonth = TimeOnCalculating::getDaysInMonth($currentMonth, $currentYear);
        $timeOnsMonth = TimeOnMonth::query()->with(['employee' => function ($q2) {
            $q2->with('department', 'position');
        }]);
        $timeOnsMonth->where('month', '=', $currentMonth)
            ->where('year', '=', $currentYear);
        if ($employee_name !== null) {
            $timeOnsMonth->whereHas('employee', function ($q) use ($employee_name) {
                $q->where('full_name', 'LIKE', '%' . $employee_name . '%');
            });
        }
        if ($employee_code !== null) {
            $timeOnsMonth->whereHas('employee', function ($q) use ($employee_code) {
                $q->where('employee_code', 'LIKE', $employee_code);
            });
        }
        if ($department_id !== 0) {
            $timeOnsMonth->whereHas('employee', function ($q) use ($department_id) {
                $q->whereHas('department', function ($q2) use ($department_id) {
                    $q2->where('id', '=', $department_id);
                });
            });
        }
        if ($job_status_id !== 0) {
            $timeOnsMonth->whereHas('employee', function ($q) use ($job_status_id) {
                $q->whereHas('jobStatus', function ($q2) use ($job_status_id) {
                    $q2->where('id', '=', $job_status_id);
                });
            });
        }
        $timeOnsMonth = $timeOnsMonth->get()->toArray();
        foreach ($timeOnsMonth as $key => $value) {
            $timeOns = TimeOn::query()->with([
                'time_offs',
                'over_times',
                'holidays'
            ])->where('time_on_month_id', '=', $value['id'])
                ->get()->toArray();
            $arrTimeOns = [];
            foreach ($daysInMonth as $v) {
                foreach ($timeOns as $val) {
                    if ($v['date'] === $val['date']) {
                        array_push($arrTimeOns, $val);
                    }
                }
            }
            $timeOnsMonth[$key]['time_ons'] = $arrTimeOns;
        }
        $result = [
            'days_in_month' => $daysInMonth,
            'time_ons_month' => $timeOnsMonth,
            'month' => $currentMonth,
            'year' => $currentYear,
        ];
        return ApiHelper::responseSuccess('List Employee', $result);
    }

    public function getTimeOnEmployee(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required',
        ], [
            'month.required' => 'month không được bỏ trống.',
        ]);

        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validate', [
                'error' => $validator->errors()->toArray()
            ]);
        }
        $month = $request->input('month');
        $month = Carbon::createFromFormat('d-n-Y', '01-' . $month);
        $currentMonth = $month->format('n');
        $currentYear = $month->format('Y');
        $daysInMonth = TimeOnCalculating::getDaysInMonth($currentMonth, $currentYear);
        $timeOnsMonth = TimeOnMonth::query()->with(['employee' => function ($q2) {
            $q2->with('department', 'position');
        }])->where('employee_id', '=', $id);
        $timeOnsMonth->where('month', '=', $currentMonth)
            ->where('year', '=', $currentYear);
        $timeOnsMonth = $timeOnsMonth->get()->toArray();
        foreach ($timeOnsMonth as $key => $value) {
            $timeOns = TimeOn::query()->with([
                'time_offs',
                'over_times',
                'holidays'
            ])->where('time_on_month_id', '=', $value['id'])
                ->get()->toArray();
            $arrTimeOns = [];
            foreach ($daysInMonth as $v) {
                foreach ($timeOns as $val) {
                    if ($v['date'] === $val['date']) {
                        array_push($arrTimeOns, $val);
                    }
                }
            }
            $timeOnsMonth[$key]['time_ons'] = $arrTimeOns;
        }
        $result = [
            'days_in_month' => $daysInMonth,
            'time_ons_month' => $timeOnsMonth,
            'month' => $currentMonth,
            'year' => $currentYear,
        ];
        return ApiHelper::responseSuccess('List Employee', $result);
    }

    public function getTotalListMonthAvailable()
    {
        $months = TimeOnMonth::query()
            ->select(DB::raw('CONCAT(year, month) AS m, month, year'))
            ->groupBy('m')
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC')
            ->get();
        $listMonths = [];
        foreach ($months as $value) {
            array_push($listMonths, $value->month . '-' . $value->year);
        }
        return ApiHelper::responseSuccess('List Months', ['months' => $listMonths]);
    }

    public function getTotalListMonthAvailableEmployee($id)
    {
        $months = TimeOnMonth::query()
            ->select(DB::raw('CONCAT(year, month) AS m, month, year'))
            ->where('employee_id', '=', $id)
            ->groupBy('m')
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC')
            ->get();
        $listMonths = [];
        foreach ($months as $value) {
            array_push($listMonths, $value->month . '-' . $value->year);
        }
        return ApiHelper::responseSuccess('List Months', ['months' => $listMonths]);
    }

    public function calculating()
    {
        try {
            DB::beginTransaction();
            TimeOnCalculating::calculateTimeOn();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseClientFail($e->getTraceAsString());
        }
        return ApiHelper::responseSuccess('Thành công');
    }

    public function getListMonthAvailable()
    {
        $months = TimeOn::query()
            ->select(DB::raw('MONTH(date) as month, YEAR(date) as year'))
            ->groupBy('month')
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC')
            ->get();
        $listMonths = [];
        foreach ($months as $value) {
            array_push($listMonths, $value->month . '-' . $value->year);
        }
        return ApiHelper::responseSuccess('List Months', ['months' => $listMonths]);
    }


    public function getTotalListYearAccumulatedAvailable()
    {
        $years = TimeOnAccumulatedYear::query()
            ->select('year')
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get();
        $listYears = [];
        foreach ($years as $value) {
            array_push($listYears, $value->year);
        }
        return ApiHelper::responseSuccess('List Months', ['years' => $listYears]);
    }



    public function addDayOffAccumulated(Request $request, $accumulated_id) {
        $time = $request->input('time');
        $reason = $request->input('reason');
        $manual_type = $request->input('manual_type');
        $month = $request->input('month');
        $year = $request->input('year');
        $accumulatedYear = TimeOnAccumulatedYear::query()
            ->where('id', '=', $accumulated_id)
            ->first();
        if ($accumulatedYear === null) {
            return ApiHelper::responseClientFail('Tích lũy không tồn tại');
        }

        $additionDayOffsAfterMonth = TimeOnAdditionDayOff::query()->where('time_on_accumulated_year_id', $accumulated_id)
            ->where(function ($q) {
                $q->where('status', '=', TIME_ON_ADDITION_DAY_OFF_STATUS_COUNT)
                    ->orWhere('status', '=', TIME_ON_ADDITION_DAY_OFF_STATUS_NOT_COUNT);
            })
            ->where('manual_type', $manual_type)
            ->where(function ($q) use ($month, $year) {
                $q->where('year', '>', $year)
                    ->orWhere(function ($q2) use ($month, $year) {
                        $q2->where('year', $year)
                            ->where('month', '>', $month);
                    });
            })
            ->get();
        $day_off_month = 0;
        foreach ($additionDayOffsAfterMonth as $value) {
            $day_off_month = $day_off_month + $value->time;
        }
        $manual_type = (int) $manual_type;
        $time = (float) $time;
        try {
            DB::beginTransaction();
            $now = Carbon::now();
            $add = new TimeOnAdditionDayOff();
            $add->time_on_accumulated_year_id = $accumulated_id;
            if ($manual_type === TIME_ON_ADDITION_DAY_OFF_MANUAL_TYPE_OT) {
                $add->time = $time - $accumulatedYear->day_off_remain_ot + $day_off_month;
            }
            if ($manual_type === TIME_ON_ADDITION_DAY_OFF_MANUAL_TYPE_PERMIT) {
                $add->time = $time - $accumulatedYear->day_off_remain_permit + $day_off_month;
            }
            $add->month = $month;
            $add->year = $year;
            $add->reason = $reason;
            $add->manual_type = $manual_type;
            $add->type = TIME_ON_ADDITION_DAY_OFF_TYPE_MANUAL;
            $add->save();
            TimeOnCalculating::calculatingAccumulatedYear($accumulatedYear->year, [$accumulatedYear->employee_id]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseClientFail('Đã có lỗi xảy ra');
        }

        $accumulated = TimeOnAccumulatedYear::query()
            ->where('id', '=', $accumulated_id)
            ->with(['employee',
                'time_on_addition_day_offs' => function ($q) {
                    $q->where('type', '=', TIME_ON_ADDITION_DAY_OFF_TYPE_MANUAL)
                        ->orWhere(function ($q2) {
                            $q2->where('type', '=', TIME_ON_ADDITION_DAY_OFF_TYPE_MONTHLY)
                                ->whereIn('status', [TIME_ON_ADDITION_DAY_OFF_STATUS_COUNT, TIME_ON_ADDITION_DAY_OFF_STATUS_NOT_COUNT]);
                        })
                        ->orWhere('type', '=', TIME_ON_ADDITION_DAY_OFF_TYPE_TIME_ON)
                        ->orWhere('type', '=', TIME_ON_ADDITION_DAY_OFF_TYPE_OT)
                        ->orderBy('month', 'DESC')
                        ->orderBy('created_at', 'DESC');
                }
            ])->first();
        return ApiHelper::responseSuccess('Tích lũy', ['accumulated' => $accumulated]);
    }
    public function removeAdditionAccumulated(Request $request, $addition_id) {
        $accumulatedAddition = TimeOnAdditionDayOff::query()
            ->where('id', '=', $addition_id)
            ->first();
        if ($accumulatedAddition === null) {
            return ApiHelper::responseClientFail('Bổ sung không tồn tại');
        }
        $accumulated = TimeOnAccumulatedYear::query()->where('id', $accumulatedAddition->time_on_accumulated_year_id)->first();
        if ($accumulated === null) {
            return ApiHelper::responseClientFail('Tích lũy không tồn tại');
        }
        try {
            DB::beginTransaction();
            $accumulatedAddition->delete();
            TimeOnCalculating::calculatingAccumulatedYear($accumulated->year, [$accumulated->employee_id]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseClientFail('Đã có lỗi xảy ra');
        }

        $accumulated = TimeOnAccumulatedYear::query()
            ->where('id', '=', $accumulated->id)
            ->with(['employee',
                'time_on_addition_day_offs' => function ($q) {
                    $q->where('type', '=', TIME_ON_ADDITION_DAY_OFF_TYPE_MANUAL)
                        ->orWhere(function ($q2) {
                            $q2->where('type', '=', TIME_ON_ADDITION_DAY_OFF_TYPE_MONTHLY)
                                ->whereIn('status', [TIME_ON_ADDITION_DAY_OFF_STATUS_COUNT, TIME_ON_ADDITION_DAY_OFF_STATUS_NOT_COUNT]);
                        })
                        ->orWhere('type', '=', TIME_ON_ADDITION_DAY_OFF_TYPE_TIME_ON)
                        ->orWhere('type', '=', TIME_ON_ADDITION_DAY_OFF_TYPE_OT)
                        ->orderBy('month', 'DESC')
                        ->orderBy('created_at', 'DESC');
                }
            ])->first();
        return ApiHelper::responseSuccess('Tích lũy', ['accumulated' => $accumulated]);
    }


    public function getTotalListAccumulate(Request $request)
    {
        $limit = $request->input('limit');
        $page = $request->input('page');
        if ($limit === null) {
            $limit = DEFAULT_PAGE_LIMIT;
        }
        if ($page === null) {
            $page = DEFAULT_DISPLAY_PAGE;
        }
        $working_status_id = $request->input('working_status_id');
        $job_status_id = $request->input('job_status_id');
        $position_id = $request->input('position_id');
        $department_id = $request->input('department_id');
        $employee_name = $request->input('employee_name');
        $employee_code = $request->input('employee_code');
        $year = $request->input('year');

        $accumulateds = TimeOnAccumulatedYear::query()
            ->with(['employee',
                'time_on_addition_day_offs' => function ($q) {
                    $q->where('type', '=', TIME_ON_ADDITION_DAY_OFF_TYPE_MANUAL)
                        ->orWhere(function ($q2) {
                            $q2->where('type', '=', TIME_ON_ADDITION_DAY_OFF_TYPE_MONTHLY)
                                ->whereIn('status', [TIME_ON_ADDITION_DAY_OFF_STATUS_COUNT, TIME_ON_ADDITION_DAY_OFF_STATUS_NOT_COUNT]);
                        })
                        ->orWhere('type', '=', TIME_ON_ADDITION_DAY_OFF_TYPE_TIME_ON)
                        ->orWhere('type', '=', TIME_ON_ADDITION_DAY_OFF_TYPE_OT)
                        ->orderBy('month', 'DESC')
                        ->orderBy('created_at', 'DESC');
                }
            ])
            ->whereHas('employee', function ($q) use (
                $working_status_id, $job_status_id, $position_id,
                $department_id, $employee_name, $employee_code, $year
            ) {
                $q->whereNull('deleted_at');
                if ($employee_name !== null) {
                    $q->where('full_name', 'LIKE', '%' . $employee_name . '%');
                }
                if ($employee_code !== null) {
                    $q->where('employee_code', 'LIKE', $employee_code);
                }
                if ($working_status_id !== null && $working_status_id !== '0') {
                    $q->where('working_status_id', '=', $working_status_id);
                }
                if ($job_status_id !== null && $job_status_id !== '0') {
                    $q->where('job_status_id', '=', $job_status_id);
                }
                if ($position_id !== null && $position_id !== '0') {
                    $q->where('position_id', '=', $position_id);
                }
                if ($department_id !== null && $department_id !== '0') {
                    $q->where('department_id', '=', $department_id);
                }
            })
            ->where('year', '=', $year);
        $paginated_data = $accumulateds->paginate($limit, ['*'], 'page', $page);
        $items = $paginated_data->items();
        $data = [
            'accumulated' => $items,
            'pagination' => [
                'total' => $paginated_data->total(),
                'per_page' => $paginated_data->perPage(),
                'current_page' => $paginated_data->currentPage(),
                'last_page' => $paginated_data->lastPage()
            ]
        ];

        return ApiHelper::responseSuccess('List Employees', $data);
    }

    public function calculatingAccumulatedYear()
    {
        TimeOnCalculating::calculatingAccumulatedYear(2018);
    }

    public function getCheckInCheckOutMonth(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required',
        ], [
            'month.required' => 'month không được bỏ trống.',
        ]);

        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validate', [
                'error' => $validator->errors()->toArray()
            ]);
        }
        $month = $request->input('month');
        $employee_name = $request->input('employee_name');
        $employee_code = $request->input('employee_code');
        $month = Carbon::createFromFormat('n-Y', $month);
        $timeOns = TimeOn::query()->with(['employee' => function ($q2) use ($employee_name, $employee_code) {
            $q2->with('department', 'position');
        }])->whereMonth('date', $month->format('n'))
            ->whereYear('date', $month->format('Y'));
        if ($employee_name !== null) {
            $timeOns->whereHas('employee', function ($q) use ($employee_name) {
                $q->where('full_name', 'LIKE', '%' . $employee_name . '%');
            });
        }
        if ($employee_code !== null) {
            $timeOns->whereHas('employee', function ($q) use ($employee_code) {
                $q->where('employee_code', 'LIKE', $employee_code);
            });
        }
        $page = $request->input('page');
        if ($page == null) {
            $page = DEFAULT_DISPLAY_PAGE;
        } else {
            $page = (int)$page;
        }
        $timeOns = $timeOns->paginate(30, ['*'], 'page', $page);
        return ApiHelper::responseSuccess('List checkin checkout', [
            'time_ons' => $timeOns->items(),
            'pagination' => [
                'total' => $timeOns->total(),
                'per_page' => $timeOns->perPage(),
                'current_page' => $timeOns->currentPage(),
                'last_page' => $timeOns->lastPage()
            ]
        ]);
    }

    public function showCheckInCheckOut(Request $request, $id)
    {
        $timeOn = TimeOn::query()->with(['employee' => function ($q2) {
            $q2->with('department', 'position');
        }])->where('id', '=', $id)
            ->first();
        if ($timeOn == null) {
            return ApiHelper::responseClientFail('Id không tồn tại');
        }
        return ApiHelper::responseSuccess('checkin checkout', ['time_on' => $timeOn]);
    }

    public function updateCheckInCheckOut(Request $request, $id)
    {
//        $validator = Validator::make($request->all(), [
//            'ot' => 'required',
//        ], [
//            'ot.required' => 'ot không được bỏ trống.',
//        ]);
//
//        if (!$validator->passes()) {
//            return ApiHelper::responseClientFail('Error Validate', [
//                'error' => $validator->errors()->toArray()
//            ]);
//        }
//        $ot = $request->input('ot');
        $check_in = $request->input('check_in');
        $check_out = $request->input('check_out');
        $timeOn = TimeOn::query()->where('id', '=', $id)
            ->first();
        if ($timeOn == null) {
            return ApiHelper::responseClientFail('Id không tồn tại');
        }
        try {
            DB::beginTransaction();
            $timeOn->check_in = $check_in;
            $timeOn->check_out = $check_out;
//            $timeOn->tc = $ot;
            if ($timeOn->check_in !== null && $timeOn->check_out !== null) {
                $morning = Carbon::createFromFormat('H:i:s', '08:00:00');
                $endMorning = Carbon::createFromFormat('H:i:s', '11:30:00');
                $startAfternoon = Carbon::createFromFormat('H:i:s', '13:00:00');
                $afternoon = Carbon::createFromFormat('H:i:s', '17:30:00');
                $in = Carbon::parse($timeOn->check_in);
                $out = Carbon::parse($timeOn->check_out);
                if ($out->lt($in)) {
                    return ApiHelper::responseClientFail('Thời gian checkout không được nhỏ hơn checkin');
                }
                $timeMorning = 0;
                $timeAfternoon = 0;
                if ($in->gt($morning)) {
                    if ($in->lte($endMorning)) {
                        $timeOn->late = $in->diffInMinutes($morning);
                        $timeMorning = 210 - $timeOn->late;
                    } else {
                        $timeMorning = 0;
                        if ($in->lte($startAfternoon)) {
                            $timeOn->late = 210;
                        } else {
                            if ($in->lte($afternoon)) {
                                $timeOn->late = 210 + $in->diffInMinutes($startAfternoon);
                            } else {
                                $timeOn->late = 480;
                            }
                        }
                    }
                } else {
                    $timeMorning = 210;
                    $timeOn->late = 0;
                }

                if ($afternoon->gt($out)) {
                    if ($out->gte($startAfternoon)) {
                        $timeOn->leave_early = $afternoon->diffInMinutes($out);
                        $timeAfternoon = 270 - $timeOn->leave_early;
                    } else {
                        $timeAfternoon = 0;
                        if ($out->gte($endMorning)) {
                            $timeOn->leave_early = 210;
                        } else {
                            if ($out->gte($morning)) {
                                $timeOn->leave_early = 270 + $out->diffInMinutes($morning);
                            } else {
                                $timeOn->leave_early = 480;
                            }
                        }
                    }
                } else {
                    $timeAfternoon = 270;
                    $timeOn->leave_early = 0;
                }
                $timeOn->hour = round(($timeMorning + $timeAfternoon) / 60, 2);
                $timeOn->working_time = round($timeOn->hour / 8, 2);
            } else {
                $timeOn->working_time = 0;
                $timeOn->leave_early = 0;
                $timeOn->late = 0;
                $timeOn->hour = 0;
            }
            $timeOn->is_updated = 1;
            $timeOn->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseClientFail($e->getMessage());
        }
        $updatedTimeOn = TimeOn::query()->with(['employee' => function ($q2) {
            $q2->with('department', 'position');
        }])->find($id);
        return ApiHelper::responseSuccess('checkin checkout', ['time_on' => $updatedTimeOn]);
    }

    public function downloadTotalListMonthAvailable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required',
            'department_id' => 'required',
        ], [
            'month.required' => 'month không được bỏ trống.',
            'department_id.required' => 'department_id không được bỏ trống.',
        ]);

        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validate', [
                'error' => $validator->errors()->toArray()
            ]);
        }
        $employee_code = $request->input('employee_code');
        $employee_name = $request->input('employee_name');
        $month = $request->input('month');
        $month = Carbon::createFromFormat('d-n-Y', '01-' . $month);
        $currentMonth = $month->format('n');
        $currentYear = $month->format('Y');
        $department_id = $request->input('department_id');
        $department_id = (int)$department_id;
        $this->export($employee_name, $employee_code, $department_id, $currentMonth, $currentYear, null);
    }

    public function downloadTotalListMonthAvailableEmployee(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required',
        ], [
            'month.required' => 'month không được bỏ trống.',
        ]);

        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validate', [
                'error' => $validator->errors()->toArray()
            ]);
        }
        $month = $request->input('month');
        $month = Carbon::createFromFormat('d-n-Y', '01-' . $month);
        $currentMonth = $month->format('n');
        $currentYear = $month->format('Y');
        $this->export(null, null, null, $currentMonth, $currentYear, $id);
    }

    public function export($employee_name, $employee_code, $department_id, $month, $year, $employee_id)
    {
        $listDaysInMonth = TimeOnCalculating::getDaysInMonth($month, $year);
        $startTimeOnColumnName = PHPExcel_Cell::stringFromColumnIndex(3);
        $endTimeOnColumnName = PHPExcel_Cell::stringFromColumnIndex(3 + sizeof($listDaysInMonth) - 1);

        $dayOffColumnIndex = 3 + sizeof($listDaysInMonth);
        $dayOffColumnName = PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex);
        Excel::create('Bảng chấm công ' . $month . '_' . $year, function ($excel) use (
            $listDaysInMonth, $startTimeOnColumnName, $endTimeOnColumnName, $dayOffColumnName, $dayOffColumnIndex,
            $employee_name, $employee_code, $department_id, $month, $year, $employee_id
        ) {

            // Set the title
            $excel->setTitle('Bảng chấm công ' . $month . '/' . $year);

            // Chain the setters
            $excel->setCreator('Ominext HRM')
                ->setCompany('Ominext');

            // Call them separately
            $excel->setDescription('A demonstration to change the file properties');

            $excel->sheet('BCC NV Fulltime', function ($sheet) use (
                $listDaysInMonth, $startTimeOnColumnName, $endTimeOnColumnName, $dayOffColumnName, $dayOffColumnIndex,
                $employee_name, $employee_code, $department_id, $month, $year, $employee_id
            ) {
                $totalColumn = 0;
                // Sets all borders
                $sheet->setAllBorders('thin');

                $sheet->mergeCells('A1:A3');
                $sheet->mergeCells('B1:B3');
                $sheet->mergeCells('C1:C3');

                // Set height for multiple rows
                $sheet->setHeight(array(
                    1 => 27,
                    2 => 27,
                    3 => 27
                ));

                // Set freeze
                $sheet->setFreeze('A4');

                // Set width for multiple cells
                $sheet->setWidth(array(
                    'A' => 7.17,
                    'B' => 7.17,
                    'C' => 27,
                ));

                $totalColumn = 3;
                $totalColumn = $totalColumn + 15;
                $totalColumn = $totalColumn + sizeof($listDaysInMonth);

                $sheet->getStyle('A1:' . PHPExcel_Cell::stringFromColumnIndex($totalColumn - 1) . '3')->getAlignment()->setWrapText(true);
                // Font family
                $sheet->setFontFamily('Times New Roman');
                // Sheet manipulation
                $sheet->cells('A1:C1', function ($cells) {

                    // manipulate the range of cells
                    $cells->setBackground('#ffff00');
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    // Set font weight to bold
                    $cells->setFontWeight('bold');


                });
                $sheet->cell('A1', function ($cell) {

                    // manipulate the cell
                    $cell->setValue('TT');

                });
                $sheet->cell('B1', function ($cell) {

                    // manipulate the cell
                    $cell->setValue('STT');

                });
                $sheet->cell('C1', function ($cell) {

                    // manipulate the cell
                    $cell->setValue('HỌ VÀ TÊN');

                });

                // Số ngày nghỉ trong tháng
                $sheet->mergeCells($dayOffColumnName . '1:' . $dayOffColumnName . '3');
                $sheet->cell($dayOffColumnName . '1', function ($cell) {
                    $cell->setValue('Số ngày nghỉ trong tháng');
                    $cell->setBackground('#f4cccc');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    // Set font weight to bold
                    $cell->setFontWeight('bold');
                    $cell->setFontColor('#000080');
                });
                $sheet->setWidth($dayOffColumnName, 7.57);
                // Số ngày được nghỉ có lương
                $dayOffWithPayColumnName = PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 1);
                $sheet->mergeCells($dayOffWithPayColumnName . '1:' . PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 2) . '2');
                $sheet->cell($dayOffWithPayColumnName . '1', function ($cell) {
                    $cell->setValue('Số ngày được nghỉ có lương');
                    $cell->setBackground('#f4cccc');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    // Set font weight to bold
                    $cell->setFontWeight('bold');
                    $cell->setFontColor('#000080');
                });

                // Số ngày được nghỉ có lương - Phép
                $dayOffPermitWithPayColumnName = PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 1);
                $sheet->cell($dayOffPermitWithPayColumnName . '3', function ($cell) {
                    $cell->setValue('Phép');
                    $cell->setBackground('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    // Set font weight to bold
                    $cell->setFontWeight('bold');
                    $cell->setFontColor('#000000');
                });
                $sheet->setWidth($dayOffPermitWithPayColumnName, 7.57);

                // Số ngày được nghỉ có lương - OT
                $dayOffOtWithPayColumnName = PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 2);
                $sheet->cell($dayOffOtWithPayColumnName . '3', function ($cell) {
                    $cell->setValue('OT');
                    $cell->setBackground('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    // Set font weight to bold
                    $cell->setFontWeight('bold');
                    $cell->setFontColor('#000000');
                });
                $sheet->setWidth($dayOffOtWithPayColumnName, 7.57);
                // Ngày nghỉ trước bù trừ giữa các tháng
                $dayOffRemainInMonthColumnName = PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 3);
                $sheet->mergeCells($dayOffRemainInMonthColumnName . '1:' . PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 4) . '2');
                $sheet->cell($dayOffRemainInMonthColumnName . '1', function ($cell) {
                    $cell->setValue('Ngày nghỉ trước bù trừ giữa các tháng');
                    $cell->setBackground('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    // Set font weight to bold
                    $cell->setFontWeight('bold');
                    $cell->setFontColor('#000000');
                });

                // Ngày nghỉ trước bù trừ giữa các tháng - Phép
                $dayOffRemainInMonthPermitColumnName = PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 3);
                $sheet->cell($dayOffRemainInMonthPermitColumnName . '3', function ($cell) {
                    $cell->setValue('Phép');
                    $cell->setBackground('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    // Set font weight to bold
                    $cell->setFontWeight('bold');
                    $cell->setFontColor('#000000');
                });
                $sheet->setWidth($dayOffRemainInMonthPermitColumnName, 7.57);
                // Ngày nghỉ trước bù trừ giữa các tháng - OT
                $dayOffRemainInMonthOtColumnName = PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 4);
                $sheet->cell($dayOffRemainInMonthOtColumnName . '3', function ($cell) {
                    $cell->setValue('OT');
                    $cell->setBackground('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    // Set font weight to bold
                    $cell->setFontWeight('bold');
                    $cell->setFontColor('#000000');
                });
                $sheet->setWidth($dayOffRemainInMonthOtColumnName, 7.57);

                // Tích lũy còn lại tháng trước
                $dayOffAccumulatedColumnName = PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 5);
                $sheet->mergeCells($dayOffAccumulatedColumnName . '1:' . PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 6) . '2');
                $sheet->cell($dayOffAccumulatedColumnName . '1', function ($cell) {
                    $cell->setValue('Tích lũy còn lại tháng trước');
                    $cell->setBackground('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    // Set font weight to bold
                    $cell->setFontWeight('bold');
                    $cell->setFontColor('#000000');
                });

                // Ngày nghỉ trước bù trừ giữa các tháng - Phép
                $dayOffAccumulatedPermitColumnName = PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 5);
                $sheet->cell($dayOffAccumulatedPermitColumnName . '3', function ($cell) {
                    $cell->setValue('Phép');
                    $cell->setBackground('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    // Set font weight to bold
                    $cell->setFontWeight('bold');
                    $cell->setFontColor('#000000');
                });
                $sheet->setWidth($dayOffAccumulatedPermitColumnName, 7.57);

                // Ngày nghỉ trước bù trừ giữa các tháng - OT
                $dayOffAccumulatedOtColumnName = PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 6);
                $sheet->cell($dayOffAccumulatedOtColumnName . '3', function ($cell) {
                    $cell->setValue('OT');
                    $cell->setBackground('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    // Set font weight to bold
                    $cell->setFontWeight('bold');
                    $cell->setFontColor('#000000');
                });
                $sheet->setWidth($dayOffAccumulatedOtColumnName, 7.57);

                // Trừ lương

                $dayOffSubtractSalaryColumnName = PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 7);
                $sheet->mergeCells($dayOffSubtractSalaryColumnName . '1:' . $dayOffSubtractSalaryColumnName . '3');
                $sheet->cell($dayOffSubtractSalaryColumnName . '1', function ($cell) {
                    $cell->setValue('Bị trừ lương');
                    $cell->setBackground('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    // Set font weight to bold
                    $cell->setFontWeight('bold');
                    $cell->setFontColor('#000000');
                });
                $sheet->setWidth($dayOffSubtractSalaryColumnName, 7.57);


                // Số ngày công hưởng lương

                $dayOffWorkDaysColumnName = PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 8);
                $sheet->mergeCells($dayOffWorkDaysColumnName . '1:' . $dayOffWorkDaysColumnName . '3');
                $sheet->cell($dayOffWorkDaysColumnName . '1', function ($cell) {
                    $cell->setValue('Số ngày công hưởng lương');
                    $cell->setBackground('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    // Set font weight to bold
                    $cell->setFontWeight('bold');
                    $cell->setFontColor('#000000');
                });
                $sheet->setWidth($dayOffWorkDaysColumnName, 7.57);

                // Còn lại
                $dayOffRemainColumnName = PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 9);
                $sheet->mergeCells($dayOffRemainColumnName . '1:' . PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 10) . '2');
                $sheet->cell($dayOffRemainColumnName . '1', function ($cell) {
                    $cell->setValue('Còn lại');
                    $cell->setBackground('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    // Set font weight to bold
                    $cell->setFontWeight('bold');
                    $cell->setFontColor('#000000');
                });


                // Còn lại - Phép
                $dayOffRemainPermitColumnName = PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 9);
                $sheet->cell($dayOffRemainPermitColumnName . '3', function ($cell) {
                    $cell->setValue('Phép');
                    $cell->setBackground('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    // Set font weight to bold
                    $cell->setFontWeight('bold');
                    $cell->setFontColor('#000000');
                });
                $sheet->setWidth($dayOffRemainPermitColumnName, 7.57);
                // Còn lại - OT
                $dayOffRemainOtColumnName = PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 10);
                $sheet->cell($dayOffRemainOtColumnName . '3', function ($cell) {
                    $cell->setValue('OT');
                    $cell->setBackground('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    // Set font weight to bold
                    $cell->setFontWeight('bold');
                    $cell->setFontColor('#000000');
                });
                $sheet->setWidth($dayOffRemainOtColumnName, 7.57);

                // Vắng mặt có phép
                $absentPermitColumnName = PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 11);
                $sheet->mergeCells($absentPermitColumnName . '1:' . $absentPermitColumnName . '3');
                $sheet->cell($absentPermitColumnName . '1', function ($cell) {
                    $cell->setValue('Số lần vắng mặt có phép');
                    $cell->setBackground('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    // Set font weight to bold
                    $cell->setFontWeight('bold');
                    $cell->setFontColor('#000000');
                });
                $sheet->setWidth($absentPermitColumnName, 7.57);

                // Vắng mặt không phép
                $absentWithoutPermitColumnName = PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 12);
                $sheet->mergeCells($absentWithoutPermitColumnName . '1:' . $absentWithoutPermitColumnName . '3');
                $sheet->cell($absentWithoutPermitColumnName . '1', function ($cell) {
                    $cell->setValue('Số lần vắng mặt không phép');
                    $cell->setBackground('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    // Set font weight to bold
                    $cell->setFontWeight('bold');
                    $cell->setFontColor('#000000');
                });
                $sheet->setWidth($absentWithoutPermitColumnName, 7.57);

                // Chuyên cần
                $diligenceColumnName = PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 13);
                $sheet->mergeCells($diligenceColumnName . '1:' . $diligenceColumnName . '3');
                $sheet->cell($diligenceColumnName . '1', function ($cell) {
                    $cell->setValue('Số lần bị phạt chuyên cần');
                    $cell->setBackground('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    // Set font weight to bold
                    $cell->setFontWeight('bold');
                    $cell->setFontColor('#000000');
                });
                $sheet->setWidth($diligenceColumnName, 7.57);

                // Notes
                $notesColumnName = PHPExcel_Cell::stringFromColumnIndex($dayOffColumnIndex + 14);
                $sheet->mergeCells($notesColumnName . '1:' . $notesColumnName . '3');
                $sheet->cell($notesColumnName . '1', function ($cell) {
                    $cell->setValue('Ghi chú');
                    $cell->setBackground('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    // Set font weight to bold
                    $cell->setFontWeight('bold');
                    $cell->setFontColor('#000000');
                });
                $sheet->setWidth($notesColumnName, 40);

                // Ngày trong tháng

                $sheet->mergeCells($startTimeOnColumnName . '1:' . $endTimeOnColumnName . '1');
                $sheet->cells($startTimeOnColumnName . '1:' . $endTimeOnColumnName . '3', function ($cells) {

                    $cells->setBackground('#92d050');
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setFontColor('#000080');
                });
                $sheet->cell($startTimeOnColumnName . '1', function ($cell) use ($month, $year) {

                    // manipulate the cell
                    $cell->setValue('NGÀY CHẤM CÔNG TRONG THÁNG ' . $month . '/ ' . $year);

                });
                foreach ($listDaysInMonth as $key => $value) {
                    $columnName = PHPExcel_Cell::stringFromColumnIndex(3 + $key);
                    $sheet->setWidth($columnName, 5.57);
                    $sheet->setBorder($columnName . '2', 'thin');
                    $sheet->cell($columnName . '2', function ($cell) use ($key) {

                        // manipulate the cell
                        $cell->setValue($key + 1);
                        // Set border for cells


                    });
                }

                $currentMonth = $month;
                $currentYear = $year;
                $daysInMonth = TimeOnCalculating::getDaysInMonth($currentMonth, $currentYear);
                if ($employee_id === null) {
                    $timeOnsMonth = TimeOnMonth::query()->with(['employee' => function ($q2) {
                        $q2->with('department', 'position');
                    }]);
                    $timeOnsMonth->where('month', '=', $month)
                        ->where('year', '=', $year);
                    if ($employee_name !== null) {
                        $timeOnsMonth->whereHas('employee', function ($q) use ($employee_name) {
                            $q->where('full_name', 'LIKE', '%' . $employee_name . '%');
                        });
                    }
                    if ($employee_code !== null) {
                        $timeOnsMonth->whereHas('employee', function ($q) use ($employee_code) {
                            $q->where('employee_code', 'LIKE', '%' . $employee_code . '%');
                        });
                    }
                    if ($department_id !== 0) {
                        $timeOnsMonth->whereHas('employee', function ($q) use ($department_id) {
                            $q->whereHas('department', function ($q2) use ($department_id) {
                                $q2->where('id', '=', $department_id);
                            });
                        });
                    }
                } else {
                    $timeOnsMonth = TimeOnMonth::query()->with(['employee' => function ($q2) {
                        $q2->with('department', 'position');
                    }])->where('employee_id', '=', $employee_id)
                        ->where('month', '=', $month)
                        ->where('year', '=', $year);;
                }

                $timeOnsMonth = $timeOnsMonth->get()->toArray();
                $filterTimeOnsMonth = [];
                foreach ($timeOnsMonth as $key => $value) {
                    $timeOns = TimeOn::query()->with([
                        'time_offs',
                        'over_times',
                        'holidays'
                    ])->where('time_on_month_id', '=', $value['id'])
                        ->get()->toArray();
                    $arrTimeOns = [];
                    foreach ($daysInMonth as $v) {
                        foreach ($timeOns as $val) {
                            if ($v['date'] === $val['date']) {
                                array_push($arrTimeOns, $val);
                            }
                        }
                    }
                    $timeOnsMonth[$key]['time_ons'] = $arrTimeOns;
                    if ($timeOnsMonth[$key]['employee'] !== null && $timeOnsMonth[$key]['employee']['department']) {
                        $filterTimeOnsMonth[$timeOnsMonth[$key]['employee']['department']['id']][] = $timeOnsMonth[$key];
                    } else {
                        $filterTimeOnsMonth[100000][] = $timeOnsMonth[$key];
                    }
                }
                $totalRows = 3;
                $totalEmployee = 0;

                $colorHoliday = '#000080';
                $colorNormal = '#ffffff';
                $colorWeekend = '#92D050';
                $colorLateWithoutPermit = '#00FF00';
                $colorLatePermit = '#FF9900';
                $colorTimeOffHalfDayPermit = '#00B0F0';
                $colorTimeOffAllDayPermit = '#7030A0';
                $colorDayOffWithoutPermit = '#CC0000';
                $colorLeavePermit = '#C0504D';
                $colorLeaveEarly = '#FFFF00';
                $colorLeaveEarlyWithoutPermit = '#176200';
                $colorLateOt = '#FFE5A3';
                $colorLeaveOut = '#be4e4d';
                $colorNoData = '#808080';
                $colorTimeOffMultiDayPermit = '#5f9ea0';
                foreach ($filterTimeOnsMonth as $key => $value) {
                    $totalEmployeeDepartment = 0;
                    $department = Department::query()->find($key);
                    $totalRows++;
                    $sheet->mergeCells('A' . $totalRows . ':' . PHPExcel_Cell::stringFromColumnIndex($totalColumn - 1) . $totalRows);
                    $sheet->cell('A' . $totalRows, function ($cell) use ($department) {

                        // manipulate the cell
                        $cell->setValue(mb_strtoupper($department->name));
                        $cell->setBackground('#2effff');
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(14);
                        $cell->setValignment('center');

                    });
                    $sheet->getStyle('A' . $totalRows)->getAlignment()->setIndent(9);
                    $sheet->getRowDimension($totalRows)->setRowHeight(27);
                    foreach ($value as $k => $v) {
                        $totalRows++;
                        $sheet->getRowDimension($totalRows)->setRowHeight(27);
                        $totalEmployee++;
                        $totalEmployeeDepartment++;
                        $sheet->cell('A' . $totalRows, function ($cell) use ($totalEmployee) {
                            // manipulate the cell
                            $cell->setValue(mb_strtoupper($totalEmployee));
                            $cell->setAlignment('left');
//                            $cell->setBackground('#2effff');
//                            $cell->setFontWeight('bold');
//                            $cell->setFontSize(14);
                            $cell->setValignment('bottom');

                        });
                        $sheet->cell('B' . $totalRows, function ($cell) use ($totalEmployeeDepartment) {
                            // manipulate the cell
                            $cell->setValue(mb_strtoupper($totalEmployeeDepartment));
                            $cell->setAlignment('left');
//                            $cell->setBackground('#2effff');
//                            $cell->setFontWeight('bold');
//                            $cell->setFontSize(14);
                            $cell->setValignment('bottom');

                        });
                        $sheet->cell('C' . $totalRows, function ($cell) use ($v) {
                            // manipulate the cell
                            $cell->setValue(mb_strtoupper($v['employee']['full_name']));
                            $cell->setAlignment('left');
                            if ($v['diligence'] === 0 && $v['absent_permit'] === 0 && $v['absent_without_permit'] === 0) {
                                $cell->setBackground('#ffff00');
                            }
//                            $cell->setBackground('#2effff');
//                            $cell->setFontWeight('bold');
//                            $cell->setFontSize(14);
                            $cell->setValignment('bottom');

                        });


                        foreach ($v['time_ons'] as $ky => $timeon) {
                            $sheet->cell(PHPExcel_Cell::stringFromColumnIndex(3 + $ky) . $totalRows, function ($cell) use ($v, $timeon,
                                $colorHoliday, $colorNormal, $colorWeekend, $colorLateWithoutPermit, $colorLatePermit, $colorTimeOffHalfDayPermit, $colorTimeOffAllDayPermit,
                                $colorDayOffWithoutPermit, $colorLeavePermit, $colorLeaveEarly, $colorLateOt, $colorLeaveOut, $colorNoData, $colorTimeOffMultiDayPermit, $colorLeaveEarlyWithoutPermit
                                ) {
                                $cell->setAlignment('left');
                                $cell->setBackground('#ffffff');
                                $cell->setValignment('bottom');
                                $cell->setAlignment('center');
                                if (TimeOnCalculating::isWeekend($timeon['date'])) {
                                    $cell->setAlignment('left');
                                    $cell->setBackground($colorWeekend);
                                    $cell->setValignment('bottom');
                                    $cell->setAlignment('center');
                                } else {
                                    if (sizeof($timeon['holidays'])) {
                                        $cell->setAlignment('left');
                                        $cell->setBackground($colorHoliday);
                                        $cell->setValignment('bottom');
                                        $cell->setAlignment('center');
                                    } else {
                                        if ($timeon['is_imported'] === 0 && $timeon['is_updated'] === 0) {
                                            $cell->setAlignment('left');
                                            $cell->setBackground($colorNoData);
                                            $cell->setValignment('bottom');
                                            $cell->setAlignment('center');
                                        } else {
                                            if ($timeon['day_off_multi_permit']) {
                                                $cell->setAlignment('left');
                                                $cell->setBackground($colorTimeOffMultiDayPermit);
                                                $cell->setValue($timeon['day_off_multi_permit']);
                                                $cell->setValignment('bottom');
                                                $cell->setAlignment('center');
                                            } else {
                                                if ($timeon['day_off_full_permit']) {
                                                    $cell->setAlignment('left');
                                                    $cell->setBackground($colorTimeOffAllDayPermit);
                                                    $cell->setValue($timeon['day_off_full_permit']);
                                                    $cell->setValignment('bottom');
                                                    $cell->setAlignment('center');
                                                } else {
                                                    if ($timeon['day_off_late_without_permit']) {
                                                        $cell->setValue($timeon['day_off_late_without_permit']);
                                                        $cell->setAlignment('left');
                                                        $cell->setBackground($colorLateWithoutPermit);
                                                        $cell->setValignment('bottom');
                                                        $cell->setAlignment('center');
                                                    }
                                                    if ($timeon['day_off_late_permit']) {
                                                        $cell->setValue($timeon['day_off_late_permit']);
                                                        $cell->setAlignment('left');
                                                        $cell->setBackground($colorLatePermit);
                                                        $cell->setValignment('bottom');
                                                        $cell->setAlignment('center');
                                                    }
                                                    if ($timeon['day_off_late_ot']) {
                                                        $cell->setValue($timeon['day_off_late_ot']);
                                                        $cell->setAlignment('left');
                                                        $cell->setBackground($colorLateOt);
                                                        $cell->setValignment('bottom');
                                                        $cell->setAlignment('center');
                                                    }
                                                    if ($timeon['day_off_leave_early_permit']) {
                                                        $cell->setValue($timeon['day_off_leave_early_permit']);
                                                        $cell->setAlignment('left');
                                                        $cell->setBackground($colorLeaveEarly);
                                                        $cell->setValignment('bottom');
                                                        $cell->setAlignment('center');
                                                    }
                                                    if ($timeon['day_off_leave_early_without_permit']) {
                                                        $cell->setValue($timeon['day_off_leave_early_without_permit']);
                                                        $cell->setAlignment('left');
                                                        $cell->setBackground($colorLeaveEarlyWithoutPermit);
                                                        $cell->setValignment('bottom');
                                                        $cell->setAlignment('center');
                                                    }
                                                    if ($timeon['day_off_without_permit']) {
                                                        $cell->setValue($timeon['day_off_without_permit']);
                                                        $cell->setAlignment('left');
                                                        $cell->setBackground($colorDayOffWithoutPermit);
                                                        $cell->setValignment('bottom');
                                                        $cell->setAlignment('center');
                                                    }
                                                    if ($timeon['day_off_go_out']) {
                                                        $cell->setValue($timeon['day_off_go_out']);
                                                        $cell->setAlignment('left');
                                                        $cell->setBackground($colorLeaveOut);
                                                        $cell->setValignment('bottom');
                                                        $cell->setAlignment('center');
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            });
                        }

                        // Số ngày nghỉ trong tháng
                        $sheet->cell($dayOffColumnName . $totalRows, function ($cell) use (&$sheet, $v) {
                            $cell->setValue($v['day_off']);
                            $cell->setBackground('#ffffff');
                            $cell->setAlignment('center');
                            $cell->setValignment('bottom');
                            // Set font weight to bold
                            $cell->setFontWeight('bold');
                            $cell->setFontColor('#000000');
                        });

                        // Số ngày nghỉ có lương trong tháng - Phép
                        $sheet->cell($dayOffPermitWithPayColumnName . $totalRows, function ($cell) use (&$sheet, $v) {
                            $cell->setValue($v['day_off_with_pay_permit']);
                            $cell->setBackground('#ffffff');
                            $cell->setAlignment('center');
                            $cell->setValignment('bottom');
                            // Set font weight to bold
                            $cell->setFontWeight('bold');
                            $cell->setFontColor('#000000');
                        });
                        // Số ngày nghỉ có lương trong tháng - OT
                        $sheet->cell($dayOffOtWithPayColumnName . $totalRows, function ($cell) use (&$sheet, $v) {
                            $cell->setValue($v['day_off_with_pay_ot']);
                            $cell->setBackground('#ffffff');
                            $cell->setAlignment('center');
                            $cell->setValignment('bottom');
                            // Set font weight to bold
                            $cell->setFontWeight('bold');
                            $cell->setFontColor('#000000');
                        });

                        // Số ngày nghỉ trước bù trừ giữa các tháng - Phép
                        $sheet->cell($dayOffRemainInMonthPermitColumnName . $totalRows, function ($cell) use (&$sheet, $v, $totalRows, $dayOffRemainInMonthPermitColumnName) {
                            $cell->setValue($v['day_off_remain_in_month_permit']);
                            $cell->setBackground('#ffffff');
                            $cell->setAlignment('center');
                            $cell->setValignment('bottom');
                            $sheet->getStyle($dayOffRemainInMonthPermitColumnName . $totalRows)->getFont()->setItalic(true);
                            $cell->setFontColor('#ff0000');
                        });
                        // Số ngày nghỉ trước bù trừ giữa các tháng - OT
                        $sheet->cell($dayOffRemainInMonthOtColumnName . $totalRows, function ($cell) use (&$sheet, $v, $dayOffRemainInMonthOtColumnName, $totalRows) {
                            $cell->setValue($v['day_off_remain_in_month_ot']);
                            $cell->setBackground('#ffffff');
                            $cell->setAlignment('center');
                            $cell->setValignment('bottom');
                            $sheet->getStyle($dayOffRemainInMonthOtColumnName . $totalRows)->getFont()->setItalic(true);
                            $cell->setFontColor('#ff0000');
                        });

                        // Tích lũy tháng trước - Phép
                        $sheet->cell($dayOffAccumulatedPermitColumnName . $totalRows, function ($cell) use (&$sheet, $v, $dayOffAccumulatedPermitColumnName, $totalRows) {
                            $cell->setValue($v['day_off_accumulated_permit']);
                            $cell->setBackground('#ffffff');
                            $cell->setAlignment('center');
                            $cell->setValignment('bottom');
                            $sheet->getStyle($dayOffAccumulatedPermitColumnName . $totalRows)->getFont()->setItalic(true);
                            $cell->setFontColor('#ff0000');
                        });
                        // Số ngày nghỉ trước bù trừ giữa các tháng - OT
                        $sheet->cell($dayOffAccumulatedOtColumnName . $totalRows, function ($cell) use (&$sheet, $v, $dayOffAccumulatedOtColumnName, $totalRows) {
                            $cell->setValue($v['day_off_accumulated_ot']);
                            $cell->setBackground('#ffffff');
                            $cell->setAlignment('center');
                            $cell->setValignment('bottom');
                            $sheet->getStyle($dayOffAccumulatedOtColumnName . $totalRows)->getFont()->setItalic(true);
                            $cell->setFontColor('#ff0000');
                        });

                        // Trừ lương
                        $sheet->cell($dayOffSubtractSalaryColumnName . $totalRows, function ($cell) use (&$sheet, $v, $dayOffSubtractSalaryColumnName, $totalRows) {
                            $cell->setValue($v['day_off_subtract_salary']);
                            if ($v['day_off_subtract_salary'] >= 0) {
                                $cell->setBackground('#ffffff');
                            } else {
                                $cell->setBackground('#ffff00');
                            }

                            $cell->setAlignment('center');
                            $cell->setValignment('bottom');
                            $sheet->getStyle($dayOffSubtractSalaryColumnName . $totalRows)->getFont()->setItalic(true);
                            $cell->setFontColor('#ff0000');
                        });

                        // Số ngày công hưởng lương
                        $sheet->cell($dayOffWorkDaysColumnName . $totalRows, function ($cell) use (&$sheet, $v, $dayOffWorkDaysColumnName, $totalRows) {
                            $cell->setValue($v['actual_work_day']);
                            $cell->setBackground('#ffffff');
                            $cell->setAlignment('center');
                            $cell->setValignment('bottom');
                            $sheet->getStyle($dayOffWorkDaysColumnName . $totalRows)->getFont()->setItalic(true);
                            $cell->setFontColor('#ff0000');
                        });

                        // Còn lại - Phép
                        $sheet->cell($dayOffRemainPermitColumnName . $totalRows, function ($cell) use (&$sheet, $v, $dayOffRemainPermitColumnName, $totalRows) {
                            $cell->setValue($v['day_off_remain_permit']);
                            $cell->setBackground('#ffffff');
                            $cell->setAlignment('center');
                            $cell->setValignment('bottom');
                            $sheet->getStyle($dayOffRemainPermitColumnName . $totalRows)->getFont()->setItalic(true);
                            $cell->setFontColor('#ff0000');
                        });

                        // Còn lại - OT
                        $sheet->cell($dayOffRemainOtColumnName . $totalRows, function ($cell) use (&$sheet, $v, $dayOffRemainOtColumnName, $totalRows) {
                            $cell->setValue($v['day_off_remain_ot']);
                            $cell->setBackground('#ffffff');
                            $cell->setAlignment('center');
                            $cell->setValignment('bottom');
                            $sheet->getStyle($dayOffRemainOtColumnName . $totalRows)->getFont()->setItalic(true);
                            $cell->setFontColor('#ff0000');
                        });

                        // Vắng mặt có phép
                        $sheet->cell($absentPermitColumnName . $totalRows, function ($cell) use (&$sheet, $v, $absentPermitColumnName, $totalRows) {
                            $cell->setValue($v['absent_permit']);
                            $cell->setBackground('#ffffff');
                            $cell->setAlignment('center');
                            $cell->setValignment('bottom');
                            $sheet->getStyle($absentPermitColumnName . $totalRows)->getFont()->setItalic(true);
                            $cell->setFontWeight('bold');
                            $cell->setFontColor('#ff0000');
                            $cell->setBackground('#f4cccc');
                        });

                        // Vắng mặt không có phép
                        $sheet->cell($absentWithoutPermitColumnName . $totalRows, function ($cell) use (&$sheet, $v, $absentWithoutPermitColumnName, $totalRows) {
                            $cell->setValue($v['absent_without_permit']);
                            $cell->setBackground('#ffffff');
                            $cell->setAlignment('center');
                            $cell->setValignment('bottom');
                            $sheet->getStyle($absentWithoutPermitColumnName . $totalRows)->getFont()->setItalic(true);
                            $cell->setFontWeight('bold');
                            $cell->setFontColor('#ff0000');
                            $cell->setBackground('#f4cccc');
                        });

                        // Chuyên cần
                        $sheet->cell($diligenceColumnName . $totalRows, function ($cell) use (&$sheet, $v, $diligenceColumnName, $totalRows) {
                            $cell->setValue($v['diligence']);
                            $cell->setBackground('#ffffff');
                            $cell->setAlignment('center');
                            $cell->setValignment('bottom');
                            $sheet->getStyle($diligenceColumnName . $totalRows)->getFont()->setItalic(true);
                            $cell->setFontColor('#ff0000');
                        });

                        // Notes
                        $sheet->cell($notesColumnName . $totalRows, function ($cell) use (&$sheet, $v, $notesColumnName, $totalRows) {
                            $cell->setValue($v['note']);
                            $cell->setBackground('#ffffff');
                            $cell->setAlignment('center');
                            $cell->setValignment('bottom');
                            $sheet->getStyle($notesColumnName . $totalRows)->getFont()->setItalic(true);
                            $cell->setFontColor('#ff0000');
                        });
                        $sheet->getStyle($notesColumnName . $totalRows)->getAlignment()->setWrapText(true);
                    }
                }


                $sheet->mergeCells('D' . ($totalRows + 2) . ':' . 'J' . ($totalRows + 2));
                $sheet->cell('D' . ($totalRows + 2), function ($cell) use ($sheet, $totalRows, $colorWeekend) {
                    $cell->setValue('GHI CHÚ');
                    $cell->setBackground('#ffffff');
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(11);
                    $cell->setValignment('center');

                });

                // Nghỉ cuối tuần
                $sheet->cell('D' . ($totalRows + 4), function ($cell) use ($sheet, $totalRows, $colorWeekend) {
                    $cell->setBackground($colorWeekend);
                    $cell->setAlignment('center');
                    $cell->setValignment('bottom');
                    $sheet->getStyle('D' . $totalRows)->getFont()->setItalic(true);
                    $cell->setFontColor('#ff0000');
                });
                $sheet->mergeCells('F' . ($totalRows + 4) . ':' . 'J' . ($totalRows + 4));
                $sheet->cell('F' . ($totalRows + 4), function ($cell) use ($sheet, $totalRows, $colorWeekend) {
                    $cell->setValue('Nghỉ cuối tuần');
                    $cell->setBackground('#ffffff');
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(11);
                    $cell->setValignment('center');

                });
                // Nghỉ lễ
                $sheet->cell('D' . ($totalRows + 6), function ($cell) use ($sheet, $totalRows, $colorHoliday) {
                    $cell->setBackground($colorHoliday);
                    $cell->setAlignment('center');
                    $cell->setValignment('bottom');
                    $sheet->getStyle('D' . $totalRows)->getFont()->setItalic(true);
                    $cell->setFontColor('#ff0000');
                });
                $sheet->mergeCells('F' . ($totalRows + 6) . ':' . 'J' . ($totalRows + 6));
                $sheet->cell('F' . ($totalRows + 6), function ($cell) use ($sheet, $totalRows, $colorHoliday) {
                    $cell->setValue('Nghỉ lễ');
                    $cell->setBackground('#ffffff');
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(11);
                    $cell->setValignment('center');

                });
                // Nghỉ nhiều ngày
                $sheet->cell('D' . ($totalRows + 8), function ($cell) use ($sheet, $totalRows, $colorTimeOffMultiDayPermit) {
                    $cell->setBackground($colorTimeOffMultiDayPermit);
                    $cell->setAlignment('center');
                    $cell->setValignment('bottom');
                    $sheet->getStyle('D' . $totalRows)->getFont()->setItalic(true);
                    $cell->setFontColor('#ff0000');
                });
                $sheet->mergeCells('F' . ($totalRows + 8) . ':' . 'J' . ($totalRows + 8));
                $sheet->cell('F' . ($totalRows + 8), function ($cell) use ($sheet, $totalRows, $colorTimeOffMultiDayPermit) {
                    $cell->setValue('Nghỉ nhiều ngày');
                    $cell->setBackground('#ffffff');
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(11);
                    $cell->setValignment('center');

                });

                // Nghỉ cả ngày
                $sheet->cell('D' . ($totalRows + 10), function ($cell) use ($sheet, $totalRows, $colorTimeOffAllDayPermit) {
                    $cell->setBackground($colorTimeOffAllDayPermit);
                    $cell->setAlignment('center');
                    $cell->setValignment('bottom');
                    $sheet->getStyle('D' . $totalRows)->getFont()->setItalic(true);
                    $cell->setFontColor('#ff0000');
                });
                $sheet->mergeCells('F' . ($totalRows + 10) . ':' . 'J' . ($totalRows + 10));
                $sheet->cell('F' . ($totalRows + 10), function ($cell) use ($sheet, $totalRows, $colorTimeOffAllDayPermit) {
                    $cell->setValue('Nghỉ cả ngày');
                    $cell->setBackground('#ffffff');
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(11);
                    $cell->setValignment('center');

                });

                // Đi muộn có phép
                $sheet->cell('D' . ($totalRows + 12), function ($cell) use ($sheet, $totalRows, $colorLatePermit) {
                    $cell->setBackground($colorLatePermit);
                    $cell->setAlignment('center');
                    $cell->setValignment('bottom');
                    $sheet->getStyle('D' . $totalRows)->getFont()->setItalic(true);
                    $cell->setFontColor('#ff0000');
                });
                $sheet->mergeCells('F' . ($totalRows + 12) . ':' . 'J' . ($totalRows + 12));
                $sheet->cell('F' . ($totalRows + 12), function ($cell) use ($sheet, $totalRows, $colorLatePermit) {
                    $cell->setValue('Đi muộn có phép');
                    $cell->setBackground('#ffffff');
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(11);
                    $cell->setValignment('center');

                });

                // Không có dữ liệu
                $sheet->cell('D' . ($totalRows + 14), function ($cell) use ($sheet, $totalRows, $colorNoData) {
                    $cell->setBackground($colorNoData);
                    $cell->setAlignment('center');
                    $cell->setValignment('bottom');
                    $sheet->getStyle('D' . $totalRows)->getFont()->setItalic(true);
                    $cell->setFontColor('#ff0000');
                });
                $sheet->mergeCells('F' . ($totalRows + 14) . ':' . 'J' . ($totalRows + 14));
                $sheet->cell('F' . ($totalRows + 14), function ($cell) use ($sheet, $totalRows, $colorNoData) {
                    $cell->setValue('Không có dữ liệu');
                    $cell->setBackground('#ffffff');
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(11);
                    $cell->setValignment('center');

                });

                // Đi muộn không phép
                $sheet->cell('M' . ($totalRows + 4), function ($cell) use ($sheet, $totalRows, $colorLateWithoutPermit) {
                    $cell->setBackground($colorLateWithoutPermit);
                    $cell->setAlignment('center');
                    $cell->setValignment('bottom');
                    $sheet->getStyle('D' . $totalRows)->getFont()->setItalic(true);
                    $cell->setFontColor('#ff0000');
                });
                $sheet->mergeCells('O' . ($totalRows + 4) . ':' . 'S' . ($totalRows + 4));
                $sheet->cell('O' . ($totalRows + 4), function ($cell) use ($sheet, $totalRows, $colorLateWithoutPermit) {
                    $cell->setValue('Đi muộn không phép');
                    $cell->setBackground('#ffffff');
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(11);
                    $cell->setValignment('center');

                });

                // Đi muộn do OT
                $sheet->cell('M' . ($totalRows + 6), function ($cell) use ($sheet, $totalRows, $colorLateOt) {
                    $cell->setBackground($colorLateOt);
                    $cell->setAlignment('center');
                    $cell->setValignment('bottom');
                    $sheet->getStyle('D' . $totalRows)->getFont()->setItalic(true);
                    $cell->setFontColor('#ff0000');
                });
                $sheet->mergeCells('O' . ($totalRows + 6) . ':' . 'S' . ($totalRows + 6));
                $sheet->cell('O' . ($totalRows + 6), function ($cell) use ($sheet, $totalRows, $colorLateOt) {
                    $cell->setValue('Đi muộn do OT');
                    $cell->setBackground('#ffffff');
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(11);
                    $cell->setValignment('center');

                });

                // Nghỉ không phép
                $sheet->cell('M' . ($totalRows + 8), function ($cell) use ($sheet, $totalRows, $colorDayOffWithoutPermit) {
                    $cell->setBackground($colorDayOffWithoutPermit);
                    $cell->setAlignment('center');
                    $cell->setValignment('bottom');
                    $sheet->getStyle('D' . $totalRows)->getFont()->setItalic(true);
                    $cell->setFontColor('#ff0000');
                });
                $sheet->mergeCells('O' . ($totalRows + 8) . ':' . 'S' . ($totalRows + 8));
                $sheet->cell('O' . ($totalRows + 8), function ($cell) use ($sheet, $totalRows, $colorDayOffWithoutPermit) {
                    $cell->setValue('Nghỉ không phép');
                    $cell->setBackground('#ffffff');
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(11);
                    $cell->setValignment('center');

                });

                // Về sớm
                $sheet->cell('M' . ($totalRows + 10), function ($cell) use ($sheet, $totalRows, $colorLeaveEarly) {
                    $cell->setBackground($colorLeaveEarly);
                    $cell->setAlignment('center');
                    $cell->setValignment('bottom');
                    $sheet->getStyle('D' . $totalRows)->getFont()->setItalic(true);
                    $cell->setFontColor('#ff0000');
                });
                $sheet->mergeCells('O' . ($totalRows + 10) . ':' . 'S' . ($totalRows + 10));
                $sheet->cell('O' . ($totalRows + 10), function ($cell) use ($sheet, $totalRows, $colorLeaveEarly) {
                    $cell->setValue('Về sớm');
                    $cell->setBackground('#ffffff');
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(11);
                    $cell->setValignment('center');

                });

                // Về sớm không phép
                $sheet->cell('M' . ($totalRows + 12), function ($cell) use ($sheet, $totalRows, $colorLeaveEarlyWithoutPermit) {
                    $cell->setBackground($colorLeaveEarlyWithoutPermit);
                    $cell->setAlignment('center');
                    $cell->setValignment('bottom');
                    $sheet->getStyle('D' . $totalRows)->getFont()->setItalic(true);
                    $cell->setFontColor('#ff0000');
                });
                $sheet->mergeCells('O' . ($totalRows + 12) . ':' . 'S' . ($totalRows + 12));
                $sheet->cell('O' . ($totalRows + 12), function ($cell) use ($sheet, $totalRows, $colorLeaveEarlyWithoutPermit) {
                    $cell->setValue('Về sớm không phép');
                    $cell->setBackground('#ffffff');
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(11);
                    $cell->setValignment('center');

                });

                // Ra ngoài
                $sheet->cell('M' . ($totalRows + 14), function ($cell) use ($sheet, $totalRows, $colorLeaveOut) {
                    $cell->setBackground($colorLeaveOut);
                    $cell->setAlignment('center');
                    $cell->setValignment('bottom');
                    $sheet->getStyle('D' . $totalRows)->getFont()->setItalic(true);
                    $cell->setFontColor('#ff0000');
                });
                $sheet->mergeCells('O' . ($totalRows + 14) . ':' . 'S' . ($totalRows + 14));
                $sheet->cell('O' . ($totalRows + 14), function ($cell) use ($sheet, $totalRows, $colorLeaveOut) {
                    $cell->setValue('Ra ngoài');
                    $cell->setBackground('#ffffff');
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(11);
                    $cell->setValignment('center');

                });


                $sheet->setAllBorders(PHPExcel_Style_Border::BORDER_MEDIUM);
            });


        })->download('xlsx');
    }
    public function updateNote(Request $request, $time_on_month_id)
    {
        $time_on_month = TimeOnMonth::query()->where('id', $time_on_month_id)->first();
        if ($time_on_month === null) {
            return ApiHelper::responseClientFail('Không tồn tại');
        }
        try {
            DB::beginTransaction();
            $time_on_month->note = $request->input('note');
            $time_on_month->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseClientFail('Đã có lỗi xảy ra');
        }
        return ApiHelper::responseSuccess('Cập nhật thành công', ['note' => $time_on_month->note]);
    }
}
