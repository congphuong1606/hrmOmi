<?php
/**
 * Created by PhpStorm.
 * User: devpc
 * Date: 5/8/2018
 * Time: 4:46 PM
 */

namespace App\Services;


use App\Models\Employee;
use App\Models\OfficialHoliday;
use App\Models\OverTimeDetail;
use App\Models\Setting;
use App\Models\TimeOff;
use App\Models\TimeOn;
use App\Models\TimeOnAccumulatedYear;
use App\Models\TimeOnAdditionDayOff;
use App\Models\TimeOnMonth;
use App\Models\TimeOnOfficialHoliday;
use App\Models\TimeOnOverTime;
use App\Models\TimeOnTimeOff;
use Carbon\Carbon;
use DB;
use Batch;

set_time_limit(0);

class TimeOnCalculating
{

    public static $startMorning = '08:00:00';
    public static $endMorning = '11:30:00';
    public static $startAfternoon = '13:00:00';
    public static $endAfternoon = '17:30:00';
    public static $late_threshold = 15;


    public static function calculateCurrentMonth()
    {
        TimeOnCalculating::calculateMonth(Carbon::now()->month, Carbon::now()->year);
    }

    public static function calculateTimeOn()
    {
        $setting = Setting::query()->first();
        if ($setting !== null) {
            TimeOnCalculating::$late_threshold = $setting->in_late_threshold;
        } else {
            TimeOnCalculating::$late_threshold = 15;
        }
        $timeOnMonthsConfirm = TimeOnMonth::query()
            ->where('is_approved', '=', 1)
            ->select(DB::raw('CONCAT_WS("-", year, month) as ym'))
            ->groupBy('ym')
            ->orderBy('ym', 'DESC')
            ->first();
        $months = TimeOn::query()
            ->select(DB::raw('MONTH(date) as month, YEAR(date) as year, CONCAT_WS("-", MONTH(date), YEAR(date)) as ym'))
            ->groupBy('month')
            ->orderBy('year', 'ASC')
            ->orderBy('month', 'ASC');
        if ($timeOnMonthsConfirm !== null) {
            $months->where('ym', '>', $timeOnMonthsConfirm->ym);
        }
        $months = $months->get();
        foreach ($months as $value) {
            TimeOnCalculating::calculateMonth($value->month, $value->year);
        }
    }

    public static function calculateMonth($currentMonth, $currentYear)
    {
        $work_days = TimeOnCalculating::getWorkDaysInMonth($currentMonth, $currentYear);
        $groupEmployeeIdTimeOns = TimeOn::query()->whereMonth('date', '=', $currentMonth)
            ->whereYear('date', '=', $currentYear)
            ->select('employee_id')
            ->with(['employee' => function ($q) {
                $q->select('user_id', 'id');
            }])
            ->whereHas('employee', function ($q) use ($currentYear, $currentMonth) {
                $q->where(function ($q10) use ($currentYear, $currentMonth) {
                    $q10->where(function ($q11) use ($currentYear, $currentMonth) {
                        $q11->whereNotNull('training_date')
                            ->where(function ($q2) use ($currentYear, $currentMonth) {
                                $q2->whereRaw('YEAR(training_date) < ' . $currentYear)
                                    ->orWhere(function ($q3) use ($currentYear, $currentMonth) {
                                        $q3->whereRaw('YEAR(training_date) = ' . $currentYear)
                                            ->whereRaw('MONTH(training_date) <= ' . $currentMonth);
                                    });
                            })->whereHas('jobStatus', function ($q2) {
                                $q2->where('code', '=', 'training');
                            });
                    })
                        ->orWhere(function ($q11) use ($currentYear, $currentMonth) {
                            $q11->whereNotNull('official_date')
                                ->where(function ($q2) use ($currentYear, $currentMonth) {
                                    $q2->whereRaw('YEAR(official_date) < ' . $currentYear)
                                        ->orWhere(function ($q3) use ($currentYear, $currentMonth) {
                                            $q3->whereRaw('YEAR(official_date) = ' . $currentYear)
                                                ->whereRaw('MONTH(official_date) <= ' . $currentMonth);
                                        });
                                })->whereHas('jobStatus', function ($q2) {
                                    $q2->where('code', '=', 'official');
                                });
                        });
                })
                    ->whereHas('workingStatus', function ($q2) {
                        $q2->where('code', '=', 'working');
                    })
                    ->whereHas('time_on_accumulated_years', function ($q2) use ($currentYear) {
                        $q2->where('year', '=', $currentYear);
                    });
            })
            ->groupBy('employee_id')
            ->get();
        //dd($groupEmployeeIdTimeOns->toArray());
        // Không tính nếu không có dữ liệu chấm công trong tháng
        if (!$groupEmployeeIdTimeOns->count()) {
            return false;
        }
        // Xóa dữ liệu chấm công tháng để làm mới
        TimeOnMonth::query()
            ->where('month', '=', $currentMonth)
            ->where('year', '=', $currentYear)
            ->delete();
        // Duyệt từng nhân viên
        $arrUpdateTimeOn = [];
        $arrUpdateTimeOnMonth = [];
        $arrInsertTimeOnAdditions = [];
        foreach ($groupEmployeeIdTimeOns as $value_employee) {
            // Danh sách time on trong tháng của nhân viên id $key_employee_id
            $timeOns = TimeOn::query()->whereMonth('date', '=', $currentMonth)
                ->whereYear('date', '=', $currentYear)
                ->where('employee_id', '=', $value_employee->employee_id)
                ->with(['employee' => function ($q) {
                    $q->select('id', 'user_id');
                }])
                ->get();
            // Lấy bảng tổng time on trong tháng của nhân viên
            $timeOnToMonth = TimeOnMonth::query()->where('month', '=', $currentMonth)
                ->where('year', '=', $currentYear)
                ->where('employee_id', '=', $value_employee->employee_id)
                ->first();
            // Nếu chưa tồn tại thì tạo mới
            if ($timeOnToMonth == null) {
                $timeOnToMonth = new TimeOnMonth();
                $timeOnToMonth->month = $currentMonth;
                $timeOnToMonth->year = $currentYear;
                $timeOnToMonth->day_off_with_pay_permit = 1;
                $timeOnToMonth->employee_id = $value_employee->employee_id;

                $timeOt = OverTimeDetail::query()->whereMonth('start_datetime', '<=', $currentMonth)
                    ->whereYear('end_datetime', '>=', $currentMonth)
                    ->where('user_id', '=', $value_employee->employee->user_id)
                    ->whereNull('deleted_at')
                    ->get();
                foreach ($timeOt as $to) {
                    $timeOnToMonth->day_off_with_pay_ot = $timeOnToMonth->day_off_with_pay_ot + TimeOnCalculating::calWorkingTime($to->start_datetime, $to->end_datetime);
                }
                $timeOnToMonth->day_off_with_pay_ot = round($timeOnToMonth->day_off_with_pay_ot / 60 / 8, 1);
            }
            // Lấy thông tin nhân viên, lưu số ngày nghỉ còn lại vào time on tháng
            $employee = Employee::query()->where('id', '=', $timeOnToMonth->employee_id)->with(['employee_late_reasons' => function ($q) {
                $q->with('late_reason');
            }])->first();


            $timeOnAccumulatedYear = TimeOnAccumulatedYear::query()
                ->where('employee_id', '=', $employee->id)
                ->where('year', '=', $currentYear)
                ->first();

            $d = TimeOnAdditionDayOff::query()->where('time_on_accumulated_year_id', $timeOnAccumulatedYear->id)
                ->where('type', TIME_ON_ADDITION_DAY_OFF_TYPE_TIME_ON)
                ->where(function ($q) use ($currentMonth, $currentYear) {
                    $q->where('year', '>', $currentYear)
                        ->orWhere(function ($q2) use ($currentMonth, $currentYear) {
                            $q2->where('year', $currentYear)
                                ->where('month', '>=', $currentMonth);
                        });
                })
                ->delete();

            TimeOnCalculating::calculatingAccumulatedYear($currentYear, [$employee->id]);

            $timeOnAccumulatedYear = TimeOnAccumulatedYear::query()
                ->where('employee_id', '=', $employee->id)
                ->where('year', '=', $currentYear)
                ->first();

            $permitMonth = TimeOnAdditionDayOff::query()->where('time_on_accumulated_year_id', $timeOnAccumulatedYear->id)
                ->where('type', TIME_ON_ADDITION_DAY_OFF_TYPE_MONTHLY)
                ->where('year', $currentYear)
                ->where('month', $currentMonth)
                ->first();
            $overtimeMonth = TimeOnAdditionDayOff::query()->where('time_on_accumulated_year_id', $timeOnAccumulatedYear->id)
                ->where('type', TIME_ON_ADDITION_DAY_OFF_TYPE_OT)
                ->where('year', $currentYear)
                ->where('month', $currentMonth)
                ->first();
            $additionDayOffs = TimeOnAdditionDayOff::query()->where('time_on_accumulated_year_id', $timeOnAccumulatedYear->id)
                ->where('type', TIME_ON_ADDITION_DAY_OFF_TYPE_MANUAL)
                ->where(function ($q) use ($currentMonth, $currentYear) {
                    $q->where('year', '>', $currentYear)
                        ->orWhere(function ($q2) use ($currentMonth, $currentYear) {
                            $q2->where('year', $currentYear)
                                ->where('month', '>=', $currentMonth);
                        });
                })
                ->get();
            $timeOnToMonth->day_off_addition_permit = 0;
            $timeOnToMonth->day_off_addition_ot = 0;
            foreach ($additionDayOffs as $v) {
                if ($v->manual_type === 1) {
                    $timeOnToMonth->day_off_addition_permit = $timeOnToMonth->day_off_addition_permit + $v->time;
                } else {
                    $timeOnToMonth->day_off_addition_ot = $timeOnToMonth->day_off_addition_ot + $v->time;
                }
            }
            $timeOnToMonth->day_off_with_pay_permit = 0;
            $timeOnToMonth->day_off_with_pay_ot = 0;
            if ($permitMonth !== null) {
                $timeOnToMonth->day_off_with_pay_permit = $permitMonth->time;
            }
            if ($overtimeMonth !== null) {
                $timeOnToMonth->day_off_with_pay_ot = $overtimeMonth->time;
            }
            $timeOnToMonth->day_off_accumulated_permit = $timeOnAccumulatedYear->day_off_remain_permit - $timeOnToMonth->day_off_with_pay_permit - $timeOnToMonth->day_off_addition_permit;
//            if ($timeOnToMonth->day_off_accumulated_permit < 0) {
//                $timeOnToMonth->day_off_accumulated_permit = 0;
//            }
            $timeOnToMonth->day_off_accumulated_ot = $timeOnAccumulatedYear->day_off_remain_ot - $timeOnToMonth->day_off_with_pay_ot - $timeOnToMonth->day_off_addition_ot;
//            if ($timeOnToMonth->day_off_accumulated_ot < 0) {
//                $timeOnToMonth->day_off_accumulated_ot = 0;
//            }
            $timeOnToMonth->day_off_without_pay = 0;
            $timeOnToMonth->day_off_remain_in_month_permit = $timeOnToMonth->day_off_with_pay_permit;
            $timeOnToMonth->day_off_remain_in_month_ot = $timeOnToMonth->day_off_with_pay_ot;
            $timeOnToMonth->day_off_subtract_salary = 0;
            $timeOnToMonth->day_off_remain_permit = $timeOnAccumulatedYear->day_off_remain_permit;
            $timeOnToMonth->day_off_remain_ot = $timeOnAccumulatedYear->day_off_remain_ot;
            $timeOnToMonth->absent_permit = 0;
            $timeOnToMonth->absent_without_permit = 0;
            $timeOnToMonth->diligence = 0;
            $timeOnToMonth->work_day = $work_days;
            $timeOnToMonth->day_off_subtract_permit = 0;
            $timeOnToMonth->day_off_subtract_ot = 0;
            $timeOnToMonth->day_off_subtract_normal = 0;
            $timeOnToMonth->save();
            // Duyệt từng time on

            foreach ($timeOns as $value) {
                TimeOnCalculating::$startMorning = '08:00:00';
                TimeOnCalculating::$endMorning = '11:30:00';
                TimeOnCalculating::$startAfternoon = '13:00:00';
                TimeOnCalculating::$endAfternoon = '17:30:00';

                if (sizeof($employee->employee_late_reasons)) {
                    $check = false;
                    foreach ($employee->employee_late_reasons as $ls) {
                        if ($ls->start_date !== null) {
                            if (Carbon::createFromFormat('Y-m-d', $value->date)->diffInDays(Carbon::createFromFormat('Y-m-d', $ls->start_date), false) <= 0) {
                                $check = true;
                                if ($ls->end_date !== null) {
                                    if (Carbon::createFromFormat('Y-m-d', $value->date)->diffInDays(Carbon::createFromFormat('Y-m-d', $ls->end_date), false) < 0) {
                                        $check = false;
                                    }
                                }
                            }
                        }
                    }
                    if ($check) {
                        TimeOnCalculating::$startMorning = $ls->late_reason->start_morning;
                        TimeOnCalculating::$endMorning = $ls->late_reason->end_morning;
                        TimeOnCalculating::$startAfternoon = $ls->late_reason->start_afternoon;
                        TimeOnCalculating::$endAfternoon = $ls->late_reason->end_afternoon;
                    }

                } else {
                    TimeOnCalculating::$startMorning = '08:00:00';
                    TimeOnCalculating::$endMorning = '11:30:00';
                    TimeOnCalculating::$startAfternoon = '13:00:00';
                    TimeOnCalculating::$endAfternoon = '17:30:00';
                }


                // Lưu giá trị id time on tổng vào time on
                $value->time_on_month_id = $timeOnToMonth->id;
                // Set default value
                $value->day_off_half_permit = 0;
                $value->day_off_full_permit = 0;
                $value->day_off_permit = 0;
                $value->work_online = 0;
                $value->day_off_late_permit = 0;
                $value->day_off_late_without_permit = 0;
                $value->day_off_go_out = 0;
                $value->day_off_leave_early_permit = 0;
                $value->day_off_holiday = 0;
                $value->day_off_late_ot = 0;
                $value->late = 0;
                $value->leave_early = 0;
                $value->day_off_leave_early_without_permit = 0;
                $value->day_off_without_permit = 0;
                $value->day_off_ot = 0;
                $value->day_off_multi_permit = 0;
                $value->day_off_total = 0;

                $currentStartMorning = Carbon::createFromFormat('Y-m-d H:i:s', $value->date . ' ' . TimeOnCalculating::$startMorning);
                $currentEndAfternoon = Carbon::createFromFormat('Y-m-d H:i:s', $value->date . ' ' . TimeOnCalculating::$endAfternoon);
                if ($value->check_in !== null) {
                    $currentCheckIn = Carbon::createFromFormat('Y-m-d H:i:s', $value->date . ' ' . $value->check_in);

                    if ($currentCheckIn->gt($currentStartMorning) && $currentCheckIn->lte($currentEndAfternoon)) {
                        $value->late = $currentCheckIn->diffInMinutes($currentStartMorning);
                    }
                }
                if ($value->check_out !== null) {
                    $currentCheckOut = Carbon::createFromFormat('Y-m-d H:i:s', $value->date . ' ' . $value->check_out);

                    if ($currentCheckOut->lt($currentEndAfternoon)) {
                        $value->leave_early = $currentEndAfternoon->diffInMinutes($currentCheckOut);
                    }
                }

                $value->day_off_leave_early_without_permit = $value->leave_early;

                // Lấy danh sách time off có trong ngày
                $timeOffs = TimeOff::query()->whereDate('start_datetime', '<=', $value->date)
                    ->whereDate('end_datetime', '>=', $value->date)
                    ->where('employee_id', '=', $value->employee_id)
                    ->where('approved', '=', 1)
                    ->whereNull('deleted_at')
                    ->get();
                // Xóa time off cũ đang nối với time on hiện tại
                TimeOnTimeOff::query()->where('time_on_id', '=', $value->id)
                    ->delete();
                // Thêm time off mới tương ứng với time on hiện tại
                foreach ($timeOffs as $key => $tf) {
                    $timeOnTimeOff = new TimeOnTimeOff();
                    $timeOnTimeOff->time_off_id = $tf->id;
                    $timeOnTimeOff->time_on_id = $value->id;
                    $timeOnTimeOff->time = TimeOnCalculating::calWorkingTime($tf->start_datetime, $tf->end_datetime);
                    $timeOnTimeOff->save();
                }
                // Lấy danh sách overtime có trong ngày
                $overtimes = OverTimeDetail::query()->whereDate('start_datetime', '<=', $value->date)
                    ->whereDate('end_datetime', '>=', $value->date)
                    ->where('user_id', '=', $value->employee->user_id)
                    ->whereHas('overtime', function ($q) {
                        $q->where('approved', '=', 1)
                            ->whereNull('deleted_at');
                    })
                    ->get();
                // Xóa overtime cũ đang nối với time on hiện tại
                TimeOnOverTime::query()->where('time_on_id', '=', $value->id)
                    ->delete();
                // Thêm overtime mới tương ứng với time on hiện tại
                foreach ($overtimes as $ovt) {
                    $timeOnOvertime = new TimeOnOverTime();
                    $timeOnOvertime->time_on_id = $value->id;
                    $timeOnOvertime->over_time_detail_id = $ovt->id;
                    $timeOnOvertime->save();
                    $startDateTimeOt = Carbon::createFromFormat('Y-m-d H:i:s', $ovt->start_datetime);
                    $endDateTimeOt = Carbon::createFromFormat('Y-m-d H:i:s', $ovt->end_datetime);
                    $currentDateOt = Carbon::createFromFormat('Y-m-d H:i:s', $value->date . ' 00:00:00');
                    if ($startDateTimeOt->diffInDays($currentDateOt)) {
                        if ($endDateTimeOt->diffInDays($currentDateOt)) {
                            $value->day_off_ot = $value->day_off_ot + 24 * 60;
                        } else {
                            $value->day_off_ot = $value->day_off_ot + $endDateTimeOt->diffInMinutes(Carbon::createFromFormat('Y-m-d H:i:s', $value->date . ' 00:00:00'));
                        };
                    } else {
                        if ($endDateTimeOt->diffInDays($currentDateOt)) {
                            $value->day_off_ot = $value->day_off_ot + Carbon::createFromFormat('Y-m-d H:i:s', $value->date . ' 23:59:59')->diffInMinutes($startDateTimeOt);
                        } else {
                            $value->day_off_ot = $value->day_off_ot + $endDateTimeOt->diffInMinutes($startDateTimeOt);
                        };
                    }
                }
                $holiday = OfficialHoliday::query()->where(function ($q) use ($value) {
                    $q->whereDate('start_date', '<=', $value->date)
                        ->whereDate('end_date', '>=', $value->date);
                })->whereNull('deleted_at')
                    ->get();
                // Xóa ngày nghỉ lễ đang nối với time on hiện tại
                TimeOnOfficialHoliday::query()->where('time_on_id', '=', $value->id)
                    ->delete();
                // Thêm ngày nghỉ lễ mới tương ứng với time on hiện tại
                foreach ($holiday as $key => $hd) {
                    $timeOnTimeOff = new TimeOnOfficialHoliday();
                    $timeOnTimeOff->official_holiday_id = $hd->id;
                    $timeOnTimeOff->time_on_id = $value->id;
                    $timeOnTimeOff->time = 480;
                    $timeOnTimeOff->save();
                }
                // Nếu có ngày nghỉ lễ thì bỏ qua không tính ngày này, thoát khỏi vòng lặp
                $value->day_off_holiday = 0;
                if ($holiday->count()) {
                    // Nếu không phải ngày cuối tuần thì đánh dấu nghỉ lễ
                    if (!TimeOnCalculating::isWeekend($value->date)) {
                        $value->day_off_holiday = 1;
                    }
                    $arraySave = $value->toArray();
                    unset($arraySave['employee']);
                    array_push($arrUpdateTimeOn, $arraySave);
                    continue;
                }
                // Nếu là ngày nghỉ cuối tuần thì bỏ qua không tính ngày này
                if (TimeOnCalculating::isWeekend($value->date)) {
                    $arraySave = $value->toArray();
                    unset($arraySave['employee']);
                    array_push($arrUpdateTimeOn, $arraySave);
                    continue;
                }
                if (!$value->is_imported && !$value->is_updated) {
                    $arraySave = $value->toArray();
                    unset($arraySave['employee']);
                    array_push($arrUpdateTimeOn, $arraySave);
                    continue;
                }
                $absent_permit = false;
                $absent_ot = false;
                $isOffAllDay = false;
                $isOffMulti = false;
                $isLatePermit = false;
                $isLeaveEarlyPermit = false;
                $isOt = false;
                foreach ($timeOffs as $v) {
                    if ($v->status === TYPE_ALL_DAY) {
                        $isOffAllDay = true;
                        $value->day_off_full_permit = 480;
                        $value->day_off_without_permit = 0;
                        if ($v->flow_type === 0) {
                            $timeOnToMonth->day_off_subtract_ot = $timeOnToMonth->day_off_subtract_ot + 480;
                            $value->day_off_total = $value->day_off_total + 480;
                        }
                        if ($v->flow_type === 1) {
                            $timeOnToMonth->day_off_subtract_permit = $timeOnToMonth->day_off_subtract_permit + 480;
                            $value->day_off_total = $value->day_off_total + 480;
                        }
                        $timeOnToMonth->absent_permit = $timeOnToMonth->absent_permit + 1;
                    }
                    if ($isOffAllDay) {
                        $arraySave = $value->toArray();
                        unset($arraySave['employee']);
                        array_push($arrUpdateTimeOn, $arraySave);
                        continue;
                    }
                    if ($v->status === TYPE_MULTIPLE_DAYS) {
                        $isOffMulti = true;
                        if ($v->flow_type === 0) {
                            $timeOnToMonth->day_off_subtract_ot = $timeOnToMonth->day_off_subtract_ot + 480;
                            $value->day_off_total = $value->day_off_total + 480;
                        }
                        if ($v->flow_type === 1) {
                            $timeOnToMonth->day_off_subtract_permit = $timeOnToMonth->day_off_subtract_permit + 480;
                            $value->day_off_total = $value->day_off_total + 480;
                        }
                        $value->day_off_multi_permit = 480;
                        $value->day_off_without_permit = 0;
                        $timeOnToMonth->absent_permit = $timeOnToMonth->absent_permit + 1;
                    }
                    if ($isOffMulti) {
                        $arraySave = $value->toArray();
                        unset($arraySave['employee']);
                        array_push($arrUpdateTimeOn, $arraySave);
                        continue;
                    }

                    if ($v->status === TYPE_DID_NOT_CHECK_OUT_CHECK_IN) {
                        if ($v->forget_type	=== 0) {
                            $value->check_in = TimeOnCalculating::$startMorning;
                            $value->late = 0;
                        }
                        if ($v->forget_type	=== 1) {
                            $value->check_out = TimeOnCalculating::$endAfternoon;
                            $value->leave_early = 0;
                        }
                        $value->is_updated = 1;
                        if ($value->check_in !== null && $value->check_out !== null) {
                            $morning = Carbon::createFromFormat('H:i:s', '08:00:00');
                            $endMorning = Carbon::createFromFormat('H:i:s', '11:30:00');
                            $startAfternoon = Carbon::createFromFormat('H:i:s', '13:00:00');
                            $afternoon = Carbon::createFromFormat('H:i:s', '17:30:00');
                            $in = Carbon::parse($value->check_in);
                            $out = Carbon::parse($value->check_out);
                            $timeMorning = 0;
                            $timeAfternoon = 0;
                            if ($in->gt($morning)) {
                                if ($in->lte($endMorning)) {
                                    $value->late = $in->diffInMinutes($morning);
                                    $timeMorning = 210 - $value->late;
                                } else {
                                    $timeMorning = 0;
                                    if ($in->lte($startAfternoon)) {
                                        $value->late = 210;
                                    } else {
                                        if ($in->lte($afternoon)) {
                                            $value->late = 210 + $in->diffInMinutes($startAfternoon);
                                        } else {
                                            $value->late = 480;
                                        }
                                    }
                                }
                            } else {
                                $timeMorning = 210;
                                $value->late = 0;
                            }

                            if ($afternoon->gt($out)) {
                                if ($out->gte($startAfternoon)) {
                                    $value->leave_early = $afternoon->diffInMinutes($out);
                                    $timeAfternoon = 270 - $value->leave_early;
                                } else {
                                    $timeAfternoon = 0;
                                    if ($out->gte($endMorning)) {
                                        $value->leave_early = 210;
                                    } else {
                                        if ($out->gte($morning)) {
                                            $value->leave_early = 270 + $out->diffInMinutes($morning);
                                        } else {
                                            $value->leave_early = 480;
                                        }
                                    }
                                }
                            } else {
                                $timeAfternoon = 270;
                                $value->leave_early = 0;
                            }
                            $value->hour = round(($timeMorning + $timeAfternoon) / 60, 2);
                            $value->working_time = round($value->hour / 8, 2);
                        } else {
                            $value->working_time = 0;
                            $value->leave_early = 0;
                            $value->late = 0;
                            $value->hour = 0;
                        }
                    }
                    if ($v->status === TYPE_IN_LATE) {
                        $isLatePermit = true;
                        $absent_permit = true;
                        $startDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $v->end_datetime);
                        $startAm = Carbon::createFromFormat('Y-m-d H:i:s', $value->date . ' ' . TimeOnCalculating::$startMorning);
                        $diff = self::calculatingWorkingTimeLateInDay($startDateTime->toTimeString());
                        if ($v->reason === OT_REASON) {
                            $value->day_off_late_ot = $diff;
                            $value->day_off_late_permit = 0;
                            $value->day_off_late_without_permit = 0;
                        } else {
                            $value->day_off_late_ot = 0;
                            $value->day_off_late_permit = $diff;
                            $value->day_off_late_without_permit = 0;
                            $timeOnToMonth->absent_permit = $timeOnToMonth->absent_permit + 1;
                        }
                        if ($v->flow_type === 0) {
                            $timeOnToMonth->day_off_subtract_ot = $timeOnToMonth->day_off_subtract_ot + $diff;
                            $value->day_off_total = $value->day_off_total + $diff;
                        }
                        if ($v->flow_type === 1) {
                            $timeOnToMonth->day_off_subtract_permit = $timeOnToMonth->day_off_subtract_permit + $diff;
                            $value->day_off_total = $value->day_off_total + $diff;
                        }
                    }
                    if ($v->status === TYPE_LEAVE_EARLY) {
                        $isLeaveEarlyPermit = true;
                        $absent_permit = true;
                        $endDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $v->start_datetime);
                        $endPm = Carbon::createFromFormat('Y-m-d H:i:s', $value->date . ' ' . TimeOnCalculating::$endAfternoon);
                        $diff = self::calculatingWorkingTimeLeaveEarlyInDay($endDateTime->toTimeString());
                        $value->day_off_leave_early_permit = $diff;
                        $value->day_off_leave_early_without_permit = 0;
                        $timeOnToMonth->absent_permit = $timeOnToMonth->absent_permit + 1;
                        if ($v->flow_type === 0) {
                            $timeOnToMonth->day_off_subtract_ot = $timeOnToMonth->day_off_subtract_ot + $diff;
                            $value->day_off_total = $value->day_off_total + $diff;
                        }
                        if ($v->flow_type === 1) {
                            $timeOnToMonth->day_off_subtract_permit = $timeOnToMonth->day_off_subtract_permit + $diff;
                            $value->day_off_total = $value->day_off_total + $diff;
                        }
                    }
                    if (
                        $v->status === TYPE_LEAVE_OUT
                    ) {
                        $diff = TimeOnCalculating::calculatingWorkingTimeLeaveOutInDay(Carbon::createFromFormat('Y-m-d H:i:s', $v->start_datetime)->toTimeString(),
                            Carbon::createFromFormat('Y-m-d H:i:s', $v->end_datetime)->toTimeString());
                        $value->day_off_go_out = $value->day_off_go_out + $diff;
                        $absent_permit = true;
                        $timeOnToMonth->absent_permit = $timeOnToMonth->absent_permit + 1;
                        if ($v->flow_type === 0) {
                            $timeOnToMonth->day_off_subtract_ot = $timeOnToMonth->day_off_subtract_ot + $diff;
                            $value->day_off_total = $value->day_off_total + $diff;
                        }
                        if ($v->flow_type === 1) {
                            $timeOnToMonth->day_off_subtract_permit = $timeOnToMonth->day_off_subtract_permit + $diff;
                            $value->day_off_total = $value->day_off_total + $diff;
                        }
                    }
                }
                if ($absent_ot) {
                    $timeOnToMonth->absent_ot = $timeOnToMonth->absent_ot + 1;
                };
                if (!$absent_permit && !$absent_ot && !$isOffAllDay && !$isOffMulti && !$isLatePermit && !$isLeaveEarlyPermit && !$isOt) {
                    if ($value->check_in === null && $value->check_out === null) {
                        $value->day_off_without_permit = 480;
                        $value->day_off_late_permit = 0;
                        $value->day_off_late_without_permit = 0;
                        $value->day_off_leave_early_without_permit = 0;
                        $timeOnToMonth->day_off_subtract_normal = $timeOnToMonth->day_off_subtract_normal + 480;
                        $value->day_off_total = $value->day_off_total + 480;
                        $timeOnToMonth->absent_without_permit = $timeOnToMonth->absent_without_permit + 1;
                    } else {
                        if ($value->late) {
                            if ($value->late <= TimeOnCalculating::$late_threshold) {
                                $value->day_off_late_permit = $value->late;
                                $value->day_off_late_without_permit = 0;
                                $timeOnToMonth->absent_permit = $timeOnToMonth->absent_permit + 1;
                            } else {
                                $value->day_off_late_without_permit = $value->late;
                                $value->day_off_late_permit = 0;
                                $timeOnToMonth->absent_without_permit = $timeOnToMonth->absent_without_permit + 1;
                            }
                            $timeOnToMonth->day_off_subtract_normal = $timeOnToMonth->day_off_subtract_normal + $value->late;
                            $value->day_off_total = $value->day_off_total + $value->late;
                        } else {
                            $value->day_off_late_without_permit = 0;
                            $value->day_off_late_permit = 0;
                        }
                        if ($value->leave_early) {
                            $value->day_off_leave_early_without_permit = $value->leave_early;
                            $timeOnToMonth->absent_without_permit = $timeOnToMonth->absent_without_permit + 1;
                            $timeOnToMonth->day_off_subtract_normal = $timeOnToMonth->day_off_subtract_normal + $value->leave_early;
                            $value->day_off_total = $value->day_off_total + $value->leave_early;
                        } else {
                            $value->day_off_leave_early_without_permit = 0;
                        }
                    }
                }
                $arraySave = $value->toArray();
                unset($arraySave['employee']);
                array_push($arrUpdateTimeOn, $arraySave);

            }
            $subtract_permit_minute = round($timeOnToMonth->day_off_subtract_permit / 60 / 8, 1);
            $subtract_ot_minute = round($timeOnToMonth->day_off_subtract_ot / 60 / 8, 1);
            $subtract_normal_minute = round($timeOnToMonth->day_off_subtract_normal / 60 / 8, 1);
            //dd($subtract_permit_minute, $subtract_ot_minute, $subtract_normal_minute, );

            $day_off = $timeOnToMonth->day_off_subtract_normal + $timeOnToMonth->day_off_subtract_permit + $timeOnToMonth->day_off_subtract_ot;
            $timeOnToMonth->day_off = round($day_off / 60 / 8, 1);
            $timeOnToMonth->diligence = 0;
            if ($timeOnToMonth->absent_permit > 5) {
                $timeOnToMonth->diligence = $timeOnToMonth->diligence + $timeOnToMonth->absent_permit - 5;
            }
            $timeOnToMonth->diligence = $timeOnToMonth->diligence + $timeOnToMonth->absent_without_permit;

            $timeOnToMonth->day_off_remain_in_month_ot = $timeOnToMonth->day_off_remain_in_month_ot - $subtract_ot_minute;
            $timeOnToMonth->day_off_remain_ot = $timeOnToMonth->day_off_remain_ot - $subtract_ot_minute;

            $timeOnToMonth->day_off_remain_in_month_permit = $timeOnToMonth->day_off_remain_in_month_permit - $subtract_permit_minute;
            $timeOnToMonth->day_off_remain_permit = $timeOnToMonth->day_off_remain_permit - $subtract_permit_minute;

            if ($timeOnToMonth->day_off_remain_ot < 0) {
                $timeOnToMonth->day_off_remain_permit = $timeOnToMonth->day_off_remain_permit + $timeOnToMonth->day_off_remain_ot;
                $timeOnToMonth->day_off_remain_in_month_permit = $timeOnToMonth->day_off_remain_in_month_permit + $timeOnToMonth->day_off_remain_ot;
                $timeOnToMonth->day_off_remain_ot = 0;
                $timeOnToMonth->day_off_remain_in_month_ot = 0;
            }
            //dd($timeOnToMonth->day_off_remain_in_month_ot, $timeOnToMonth->day_off_remain_ot,$timeOnToMonth->day_off_remain_in_month_permit, $timeOnToMonth->day_off_remain_permit);
            if ($timeOnToMonth->day_off_remain_in_month_ot > 0) {
                $timeOnToMonth->day_off_remain_in_month_ot = $timeOnToMonth->day_off_remain_in_month_ot - $subtract_normal_minute;
                $timeOnToMonth->day_off_remain_ot = $timeOnToMonth->day_off_remain_ot - $subtract_normal_minute;
                if ($timeOnToMonth->day_off_remain_in_month_ot < 0) {
                    $timeOnToMonth->day_off_remain_permit = $timeOnToMonth->day_off_remain_permit + $timeOnToMonth->day_off_remain_in_month_ot;
                    $timeOnToMonth->day_off_remain_in_month_permit = $timeOnToMonth->day_off_remain_in_month_permit + $timeOnToMonth->day_off_remain_in_month_ot;
                    $timeOnToMonth->day_off_remain_in_month_ot = $timeOnToMonth->day_off_remain_in_month_ot - $timeOnToMonth->day_off_remain_in_month_ot - $subtract_normal_minute;
                    $timeOnToMonth->day_off_remain_in_month_ot = 0;
                }
            } else {
                $timeOnToMonth->day_off_remain_permit = $timeOnToMonth->day_off_remain_permit - $subtract_normal_minute;
                $timeOnToMonth->day_off_remain_in_month_permit = $timeOnToMonth->day_off_remain_in_month_permit - $subtract_normal_minute;
            }

            $timeOnToMonth->day_off_subtract_salary = $timeOnToMonth->day_off_remain_permit + $timeOnToMonth->day_off_remain_ot;
            if ($employee->training_date !== null) {
                $timeOnToMonth->actual_work_day = self::getWorkingDaysInMonth($employee->training_date, $timeOns, $currentMonth, $currentYear);
            }
            if ($employee->official_date !== null) {
                $timeOnToMonth->actual_work_day = self::getWorkingDaysInMonth($employee->official_date, $timeOns, $currentMonth, $currentYear);
            }

            if ($timeOnToMonth->day_off_subtract_salary < 0) {
                $timeOnToMonth->actual_work_day = $timeOnToMonth->actual_work_day + $timeOnToMonth->day_off_subtract_salary;
            }
            if ($timeOnToMonth->day_off_remain_permit < 0) {
                $timeOnToMonth->day_off_remain_permit = 0;
            }
            if ($timeOnToMonth->day_off_remain_ot < 0) {
                $timeOnToMonth->day_off_remain_ot = 0;
            }
            //$timeOnToMonth->save();
            array_push($arrUpdateTimeOnMonth, $timeOnToMonth->toArray());
            $addDayOffPermitAfterCal = $timeOnAccumulatedYear->day_off_remain_permit - $timeOnToMonth->day_off_remain_permit;
            $addDayOffOtAfterCal = $timeOnAccumulatedYear->day_off_remain_ot - $timeOnToMonth->day_off_remain_ot;
//            dd($addDayOffPermitAfterCal, $addDayOffOtAfterCal);
            if ($addDayOffPermitAfterCal != 0) {
                $addPermit = new TimeOnAdditionDayOff();
                $addPermit->type = TIME_ON_ADDITION_DAY_OFF_TYPE_TIME_ON;
                $addPermit->manual_type = TIME_ON_ADDITION_DAY_OFF_MANUAL_TYPE_PERMIT;
                $addPermit->month = $currentMonth;
                $addPermit->year = $currentYear;
                $addPermit->time = $addDayOffPermitAfterCal * (-1);
                $addPermit->time_on_accumulated_year_id = $timeOnAccumulatedYear->id;
                $addPermit->reason = "Chấm công tháng $currentMonth-$currentYear";
                $addPermit->created_at = Carbon::now()->toDateString();
                $addPermit->updated_at = Carbon::now()->toDateString();
                array_push($arrInsertTimeOnAdditions, $addPermit->toArray());
            }

            if ($addDayOffOtAfterCal != 0) {
                $addOt = new TimeOnAdditionDayOff();
                $addOt->type = TIME_ON_ADDITION_DAY_OFF_TYPE_TIME_ON;
                $addOt->manual_type = TIME_ON_ADDITION_DAY_OFF_MANUAL_TYPE_OT;
                $addOt->month = $currentMonth;
                $addOt->year = $currentYear;
                $addOt->time = $addDayOffOtAfterCal * (-1);
                $addOt->time_on_accumulated_year_id = $timeOnAccumulatedYear->id;
                $addOt->reason = "Chấm công tháng $currentMonth-$currentYear";
                $addOt->created_at = Carbon::now()->toDateString();
                $addOt->updated_at = Carbon::now()->toDateString();
                array_push($arrInsertTimeOnAdditions, $addOt->toArray());
            }
        }
        foreach (array_chunk($arrUpdateTimeOn, 500) as $value) {
            Batch::update('time_on', $value, 'id');
        }
        foreach (array_chunk($arrUpdateTimeOnMonth, 500) as $value) {
            Batch::update('time_on_month', $value, 'id');
        }

        Batch::insert('time_on_addition_day_off', [
            'type', 'manual_type', 'month', 'year', 'time', 'time_on_accumulated_year_id', 'reason', 'created_at', 'updated_at'
        ], $arrInsertTimeOnAdditions, 500);
        self::calculatingAccumulatedYear($currentYear);
        return true;
    }

    public static function getWorkingDaysInMonth($official_date, $time_on, $current_month, $current_year)
    {
        $now = Carbon::now();
        $currentMonth = Carbon::createFromDate($current_year, $current_month);
        $firstMonth = $now->copy()->firstOfMonth();
        $official_date = Carbon::createFromFormat('Y-m-d', $official_date);
        // Năm chính thức lớn hơn năm đang tính thì trả về 0
        if ($official_date->year > $currentMonth->year) {
            return 0;
        }
        // Nếu năm chính thức = năm tính và tháng chính thức lớn hơn tháng tính thì trả về 0
        if ($official_date->year === $currentMonth->year && $official_date->month > $currentMonth->month) {
            return 0;
        }
        // Nếu năm chính thức bằng năm tính và tháng chính thức bằng tháng tính và ngày chính thức
        if ($official_date->year === $currentMonth->year && $official_date->month == $currentMonth->month) {
            if ($now->year > $currentMonth->year || ($now->year === $currentMonth->year && $now->month > $currentMonth->month)) {
                $startDate = $official_date->copy();
                $endDate = $currentMonth->copy()->lastOfMonth();
            } else {
                $startDate = $official_date->copy();
                $endDate = $now->copy()->lastOfMonth();
            }
        }

        if (($official_date->year === $currentMonth->year && $official_date->month < $currentMonth->month) || $official_date->year < $currentMonth->year) {
            if ($now->year > $currentMonth->year || ($now->year === $currentMonth->year && $now->month > $currentMonth->month)) {
                $startDate = $currentMonth->copy()->firstOfMonth();
                $endDate = $currentMonth->copy()->lastOfMonth();
            } else {
                $startDate = $currentMonth->copy()->firstOfMonth();
                $endDate = $now->copy()->lastOfMonth();
            }
        }

        $count = 0;
        for ($date = $startDate->copy(); $date->diffInDays($endDate, false) >= 0; $date->addDay()) {
            if ($date->dayOfWeek !== 0 && $date->dayOfWeek !== 6) {
                $check = true;
                foreach ($time_on as $value) {
                    if ($date->toDateString() === $value->date && !$value->is_imported && !$value->is_updated) {
                        $check = false;
                    }
                }
                if ($check) $count++;
            }
        }
        return $count;
    }

    public static function updateValues(array $values)
    {
        $table = TimeOn::query()->getModel()->getTable();

        $cases = [];
        $ids = [];
        $params = [];

        foreach ($values as $id => $value) {
            $id = (int)$id;
            $cases[] = "WHEN {$id} then ?";
            $params[] = $value;
            $ids[] = $id;
        }

        $ids = implode(',', $ids);
        $cases = implode(' ', $cases);
        $params[] = Carbon::now();

        return \DB::update("UPDATE `{$table}` SET `time_on_month_id` = CASE `id` {$cases} END, `updated_at` = ? WHERE `id` in ({$ids})", $params);
    }


    public static function calWorkingTime($start, $end)
    {
        $start_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $start);
        $end_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $end);
        return $end_datetime->diffInMinutes($start_datetime);
    }

    public static function isWeekend($date)
    {
        $currentDate = Carbon::createFromFormat('Y-m-d', $date)->dayOfWeek;
        if ($currentDate === 0 || $currentDate === 6) {
            return true;
        }
        return false;
    }

    public static function getDaysInMonth($month, $year)
    {
        $lastMonth = Carbon::createFromFormat('Y-n-d', $year . '-' . $month . '-01')->lastOfMonth();
        $firstMonth = Carbon::createFromFormat('Y-n-d', $year . '-' . $month . '-01')->startOfMonth();

        $dates = [];

        for ($date = $firstMonth; $date->lte($lastMonth); $date->addDay()) {
            array_push($dates, [
                'date' => $date->toDateString(),
                'day_of_week' => $date->dayOfWeek,
                'day_in_month' => $date->day,
            ]);
        }
        return $dates;
    }

    public static function getWorkDaysInMonth($month, $year)
    {
        $lastMonth = Carbon::createFromFormat('Y-n-d', $year . '-' . $month . '-01')->lastOfMonth();
        $firstMonth = Carbon::createFromFormat('Y-n-d', $year . '-' . $month . '-01')->startOfMonth();

        $count = 0;

        for ($date = $firstMonth; $date->lte($lastMonth); $date->addDay()) {
            if ($date->dayOfWeek !== 0 && $date->dayOfWeek !== 6) {
                $count++;
            }
        }
        return $count;
    }

    public static function calculatingAccumulatedYear($year, $id = [])
    {
        $employees = Employee::query()
            ->whereNull('deleted_at')
            ->where(function ($q) {
                $q->whereHas('jobStatus', function ($q3) {
                    $q3->where('code', '=', 'official')
                        ->whereNotNull('official_date');
                })->orWhereHas('jobStatus', function ($q3) {
                    $q3->where('code', '=', 'training')
                        ->whereNotNull('training_date');
                });
            })
            ->whereHas('workingStatus', function ($q) {
                $q->where('code', '=', 'working');
            });
        if (sizeof($id)) {
            $employees->whereIn('id', $id);
        }
        $employees = $employees->get();
        foreach ($employees as $value) {
            $currentAccumulatedYear = TimeOnAccumulatedYear::query()
                ->where('year', '=', $year)
                ->where('employee_id', '=', $value->id)
                ->first();
            $arrMonths = [];
            if ($currentAccumulatedYear === null) {
                $currentAccumulatedYear = new TimeOnAccumulatedYear();
                $currentAccumulatedYear->employee_id = $value->id;
                $currentAccumulatedYear->year = $year;
                $previousYear = $year - 1;
                $previousAccumulatedYear = TimeOnAccumulatedYear::query()
                    ->where('year', '=', $previousYear)
                    ->where('employee_id', '=', $value->id)
                    ->first();
                if ($value->official_date !== null) {
                    $official_date = Carbon::createFromFormat('Y-m-d', $value->official_date);
                    if ($official_date->year <= $year) {
                        if ($official_date->year <= $previousYear) {
                            if ($previousAccumulatedYear !== null) {
                                $resetDate = self::getTimeResetDayoff($year);
                                if (Carbon::now()->gte($resetDate)) {
                                    $previousAccumulatedYear->day_off_accumulated_permit_before_reset = $previousAccumulatedYear->day_off_remain_permit;
                                    $previousAccumulatedYear->day_off_remain_permit = 0;
                                    $previousAccumulatedYear->reset_time = $resetDate->toDateString();
                                    $previousAccumulatedYear->save();
                                }
                                $currentAccumulatedYear->day_off_accumulated_permit_previous_year = $previousAccumulatedYear->day_off_remain_permit;
                                $currentAccumulatedYear->day_off_accumulated_ot_previous_year = $previousAccumulatedYear->day_off_remain_ot;
                            }
                            $official_date = Carbon::createFromFormat('Y-m-d H:i:s', $year . '-01-01 00:00:00');
                        }
                        $currentAccumulatedYear->save();
                        $now = Carbon::createFromDate($year);
                        $count = 0;
                        if ($official_date->lte($now)) {
                            $dem = 0;
                            $count++;
                            for ($date = $official_date; $date->month <= $now->month; $date->addMonth()) {
                                $timeonMonth = TimeOnMonth::query()
                                    ->where('employee_id', '=', $value->id)
                                    ->where('month', '=', $date->month)
                                    ->where('year', '=', $date->year)
                                    ->first();
                                $timeOnAdditionDayOff = new TimeOnAdditionDayOff();
                                $timeOnAdditionDayOff->time_on_accumulated_year_id = $currentAccumulatedYear->id;
                                $timeOnAdditionDayOff->time = 1;
                                $timeOnAdditionDayOff->type = TIME_ON_ADDITION_DAY_OFF_TYPE_MONTHLY;
                                $timeOnAdditionDayOff->status = TIME_ON_ADDITION_DAY_OFF_STATUS_NOT_COUNT;
                                $timeOnAdditionDayOff->month = $date->month;
                                $timeOnAdditionDayOff->year = $date->year;
                                if ($timeonMonth !== null && $timeonMonth->is_approved) {
                                    $timeOnAdditionDayOff->status = TIME_ON_ADDITION_DAY_OFF_STATUS_COUNT;

                                }
                                $dem++;
                                if ($dem === 1) {
                                    if ($date->lte(Carbon::createFromFormat('Y-m-d', $date->year . '-' . $date->month . '-15'))) {
                                        $timeOnAdditionDayOff->save();
                                        array_push($arrMonths, $date->month);
                                    }
                                } else {
                                    $timeOnAdditionDayOff->save();
                                    array_push($arrMonths, $date->month);
                                }
                            }
                        }
                    }
                };
//                $official_date = Carbon::createFromFormat('Y-m-d', $value->official_date);

                // Tính OT
                if ($value->official_date !== null) {
                    $official_date = Carbon::createFromFormat('Y-m-d', $value->official_date);
                };
                if ($value->training_date !== null) {
                    $official_date = Carbon::createFromFormat('Y-m-d', $value->training_date);
                };
                //$official_date = Carbon::createFromFormat('Y-m-d', $value->official_date);
                if ($official_date->year <= $year) {
                    // Lấy danh sách overtime có trong ngày
                    $overtimes = OverTimeDetail::query()
                        ->where(function ($q) use ($year) {
                            $q->where(function ($q2) use ($year) {
                                $q2->where(DB::raw('YEAR(start_datetime)'), '=', $year - 1)
                                    ->where(DB::raw('YEAR(end_datetime)'), '=', $year);
                            })
                                ->orWhere(function ($q2) use ($year) {
                                    $q2->where(DB::raw('YEAR(start_datetime)'), '=', $year)
                                        ->where(DB::raw('YEAR(end_datetime)'), '=', $year);
                                })
                                ->orWhere(function ($q2) use ($year) {
                                    $q2->where(DB::raw('YEAR(start_datetime)'), '=', $year)
                                        ->where(DB::raw('YEAR(end_datetime)'), '=', $year + 1);
                                });
                        })
                        ->where('user_id', '=', $value->user_id)
                        ->whereHas('overtime', function ($q) {
                            $q->where('approved', '=', 1)
                                ->whereNull('deleted_at');
                        });
                    if ($official_date->year == $year) {
                        $overtimes->whereDate(DB::raw('DATE(start_datetime)'), '>=', $official_date->toDateString());
                    }

                    $overtimes = $overtimes->get();;
                    $arrMonthOt = [];
                    foreach ($overtimes as $ovt) {
                        $startDateTimeOt = Carbon::createFromFormat('Y-m-d H:i:s', $ovt->start_datetime);
                        $endDateTimeOt = Carbon::createFromFormat('Y-m-d H:i:s', $ovt->end_datetime);
                        if ($startDateTimeOt->gt($endDateTimeOt)) continue;
                        $timeOt = 0;
                        if (!$startDateTimeOt->diffInDays($endDateTimeOt)) {
                            //$timeOt = $startDateTimeOt->diffInMinutes($endDateTimeOt);
                            $timeOt = self::calculatingWorkingTimeOtInDay($startDateTimeOt->toTimeString(), $endDateTimeOt->toTimeString());
                            if (array_key_exists($startDateTimeOt->month, $arrMonthOt)) {
                                $arrMonthOt[$startDateTimeOt->month] = $arrMonthOt[$startDateTimeOt->month] + $timeOt;
                            } else {
                                $arrMonthOt[$startDateTimeOt->month] = $timeOt;
                            }
                        } else {
                            $count = 0;
                            for ($dateOt = $startDateTimeOt->copy(); $dateOt->diffInDays($endDateTimeOt, false) >= 0; $dateOt->addDay()) {
                                if ($dateOt->year !== $year) {
                                    continue;
                                }
                                $count++;
                                if ($dateOt->diffInDays($startDateTimeOt) === 0) {
                                    //$timeOt = $startDateTimeOt->diffInMinutes(Carbon::createFromFormat('Y-m-d H:i:s', $startDateTimeOt->copy()->addDay()->toDateString() . ' 00:00:00'));
                                    $timeOt = self::calculatingWorkingTimeOtInDay($startDateTimeOt->toTimeString(), '23:59:59');
                                } else {
                                    if ($dateOt->diffInDays($endDateTimeOt) === 0) {
                                        $timeOt = self::calculatingWorkingTimeOtInDay($endDateTimeOt->toTimeString(), '23:59:59');
                                    } else {
                                        $timeOt = 24 * 60 - 150;
                                    }
                                }
                                if (array_key_exists($dateOt->month, $arrMonthOt)) {
                                    $arrMonthOt[$dateOt->month] = $arrMonthOt[$dateOt->month] + $timeOt;
                                } else {
                                    $arrMonthOt[$dateOt->month] = $timeOt;
                                }
                            }
                        }
                    }
                    foreach ($arrMonthOt as $key => $v) {
                        $addOt = new TimeOnAdditionDayOff();
                        $addOt->time_on_accumulated_year_id = $currentAccumulatedYear->id;
                        $addOt->time = round($v / 60 / 8, 1);
                        $addOt->type = TIME_ON_ADDITION_DAY_OFF_TYPE_OT;
                        $addOt->manual_type = TIME_ON_ADDITION_DAY_OFF_MANUAL_TYPE_OT;
                        $addOt->status = TIME_ON_ADDITION_DAY_OFF_STATUS_NOT_COUNT;
                        $addOt->month = $key;
                        $addOt->year = $year;
                        $addOt->status = TIME_ON_ADDITION_DAY_OFF_STATUS_COUNT;
                        $addOt->save();
                    }

                }
                $timeAdds = TimeOnAdditionDayOff::query()
                    ->where('time_on_accumulated_year_id', '=', $currentAccumulatedYear->id)
                    ->where(function ($q) {
                        $q->where('status', '=', TIME_ON_ADDITION_DAY_OFF_STATUS_COUNT)
                            ->orWhere('status', '=', TIME_ON_ADDITION_DAY_OFF_STATUS_NOT_COUNT);
                    })
                    ->where('year', '=', $year)
                    ->get();
                $day_off_permit_in_year = 0;
                $day_off_ot_in_year = 0;
                foreach ($timeAdds as $v) {
                    if ($v->manual_type === TIME_ON_ADDITION_DAY_OFF_MANUAL_TYPE_PERMIT) {
                        $day_off_permit_in_year = $day_off_permit_in_year + $v->time;
                    }
                    if ($v->manual_type === TIME_ON_ADDITION_DAY_OFF_MANUAL_TYPE_OT) {
                        $day_off_ot_in_year = $day_off_ot_in_year + $v->time;
                    }
                }

                if ($day_off_permit_in_year < 0) {
                    $day_off_permit_in_year = 0;
                }
                if ($day_off_ot_in_year < 0) {
                    $day_off_ot_in_year = 0;
                }
                $currentAccumulatedYear->day_off_permit_in_year = $day_off_permit_in_year;
                $currentAccumulatedYear->day_off_ot_in_year = $day_off_ot_in_year;
                $currentAccumulatedYear->day_off_remain_permit = $currentAccumulatedYear->day_off_permit_in_year + $currentAccumulatedYear->day_off_accumulated_permit_previous_year;
                $currentAccumulatedYear->day_off_remain_ot = $currentAccumulatedYear->day_off_remain_ot + $currentAccumulatedYear->day_off_accumulated_ot_previous_year;
                if (sizeof($arrMonths)) {
                    $currentAccumulatedYear->from_month = $arrMonths[0];
                    $currentAccumulatedYear->end_month = $arrMonths[sizeof($arrMonths) - 1];
                }
                $currentAccumulatedYear->save();

            } else {
                $manualAddDayOff = TimeOnAdditionDayOff::query()
                    ->where('year', '=', $year)
                    ->where('type', '=', TIME_ON_ADDITION_DAY_OFF_TYPE_MANUAL)
                    ->where('time_on_accumulated_year_id', '=', $currentAccumulatedYear->id)
                    ->get();

                foreach ($manualAddDayOff as $v) {
                    if ($value->official_date == null && $value->training_date == null) {
                        $v->delete();
                    } else {
                        if ($value->official_date !== null) {
                            $official_date = Carbon::createFromFormat('Y-m-d', $value->official_date);
                        };
                        if ($value->training_date !== null) {
                            $official_date = Carbon::createFromFormat('Y-m-d', $value->training_date);
                        };
//                        $official_date = Carbon::createFromFormat('Y-m-d', $value->official_date);
                        if (Carbon::createFromFormat('Y-m-d', $v->created_at->toDateString())->lt($official_date)) {
                            $v->delete();
                        }
                    }
                }
                $timeOnAddDayOff = TimeOnAdditionDayOff::query()
                    ->where('year', '=', $year)
                    ->whereIn('type', [TIME_ON_ADDITION_DAY_OFF_TYPE_TIME_ON])
                    ->where('time_on_accumulated_year_id', '=', $currentAccumulatedYear->id)
                    ->get();
                TimeOnAdditionDayOff::query()
                    ->where('year', '=', $year)
                    ->where('type', TIME_ON_ADDITION_DAY_OFF_TYPE_OT)
                    ->where('time_on_accumulated_year_id', '=', $currentAccumulatedYear->id)
                    ->delete();
                foreach ($timeOnAddDayOff as $v) {
                    if ($value->official_date == null && $value->training_date == null) {
                        $v->delete();
                    } else {
                        if ($value->official_date !== null) {
                            $official_date = Carbon::createFromFormat('Y-m-d', $value->official_date);
                        };
                        if ($value->training_date !== null) {
                            $official_date = Carbon::createFromFormat('Y-m-d', $value->training_date);
                        };
                        //$official_date = Carbon::createFromFormat('Y-m-d', $value->official_date);
                        if ($official_date->year > $v->year) {
                            $v->delete();
                        }
                        if ($official_date->year === $v->year && $official_date->month > $v->month) {
                            $v->delete();
                        }
                    }
                }
                $currentAccumulatedYear->employee_id = $value->id;
                $currentAccumulatedYear->year = $year;
                $previousYear = $year - 1;
                $previousAccumulatedYear = TimeOnAccumulatedYear::query()
                    ->where('year', '=', $previousYear)
                    ->where('employee_id', '=', $value->id)
                    ->first();
                if ($value->official_date !== null) {
                    $official_date = Carbon::createFromFormat('Y-m-d', $value->official_date);
                    if ($official_date->year <= $year) {
                        if ($official_date->year <= $previousYear) {
                            if ($previousAccumulatedYear !== null) {
                                $resetDate = self::getTimeResetDayoff($year);
                                if (Carbon::now()->gte($resetDate)) {
                                    $previousAccumulatedYear->day_off_accumulated_permit_before_reset = $previousAccumulatedYear->day_off_remain_permit;
                                    $previousAccumulatedYear->day_off_remain_permit = 0;
                                    $previousAccumulatedYear->reset_time = $resetDate->toDateString();
                                    $previousAccumulatedYear->save();
                                }
                                $currentAccumulatedYear->day_off_accumulated_permit_previous_year = $previousAccumulatedYear->day_off_remain_permit;
                                $currentAccumulatedYear->day_off_accumulated_ot_previous_year = $previousAccumulatedYear->day_off_remain_ot;
                            }
                            $official_date = Carbon::createFromFormat('Y-m-d H:i:s', $year . '-01-01 00:00:00');
                        }
                        $currentAccumulatedYear->save();
                        $now = Carbon::createFromDate($year);
                        if ($official_date->lte($now)) {
                            $dem = 0;

                            for ($date = $official_date; $date->month <= $now->month; $date->addMonth()) {
                                $dem++;
                                $timeonMonth = TimeOnMonth::query()
                                    ->where('employee_id', '=', $value->id)
                                    ->where('month', '=', $date->month)
                                    ->where('year', '=', $date->year)
                                    ->first();
                                $timeOnAdditionDayOff = TimeOnAdditionDayOff::query()
                                    ->where('type', '=', TIME_ON_ADDITION_DAY_OFF_TYPE_MONTHLY)
                                    ->where('time_on_accumulated_year_id', '=', $currentAccumulatedYear->id)
                                    ->where('month', '=', $date->month)
                                    ->where('year', '=', $date->year)
                                    ->first();
                                if ($timeOnAdditionDayOff === null) {
                                    $timeOnAdditionDayOff = new TimeOnAdditionDayOff();
                                }
                                $timeOnAdditionDayOff->time_on_accumulated_year_id = $currentAccumulatedYear->id;
                                $timeOnAdditionDayOff->time = 1;
                                $timeOnAdditionDayOff->type = TIME_ON_ADDITION_DAY_OFF_TYPE_MONTHLY;
                                $timeOnAdditionDayOff->status = TIME_ON_ADDITION_DAY_OFF_STATUS_NOT_COUNT;
                                $timeOnAdditionDayOff->month = $date->month;
                                $timeOnAdditionDayOff->year = $date->year;
                                if ($timeonMonth !== null && $timeonMonth->is_approved) {
                                    $timeOnAdditionDayOff->status = TIME_ON_ADDITION_DAY_OFF_STATUS_COUNT;
                                }

                                if ($dem === 1) {
                                    if ($date->lte(Carbon::createFromFormat('Y-m-d', $date->year . '-' . $date->month . '-15'))) {
                                        $timeOnAdditionDayOff->save();
                                        array_push($arrMonths, $date->month);
                                    }
                                } else {
                                    $timeOnAdditionDayOff->save();
                                    array_push($arrMonths, $date->month);
                                }
                            }
                            $deleteTimeAdds = TimeOnAdditionDayOff::query()
                                ->where('type', '=', TIME_ON_ADDITION_DAY_OFF_TYPE_MONTHLY)
                                ->where('time_on_accumulated_year_id', '=', $currentAccumulatedYear->id)
                                ->where('year', '=', $date->year);
                            if (sizeof($arrMonths)) {
                                $deleteTimeAdds->whereNotIn('month', $arrMonths);
                            };
                            $deleteTimeAdds = $deleteTimeAdds->delete();
                        }
                    }
                };
                //$official_date = Carbon::createFromFormat('Y-m-d', $value->official_date);


                // Tính OT
                if ($value->official_date !== null) {
                    $official_date = Carbon::createFromFormat('Y-m-d', $value->official_date);
                };
                if ($value->training_date !== null) {
                    $official_date = Carbon::createFromFormat('Y-m-d', $value->training_date);
                };
                //$official_date = Carbon::createFromFormat('Y-m-d', $value->official_date);
                if ($official_date->year <= $year) {
                    // Lấy danh sách overtime có trong ngày
                    $overtimes = OverTimeDetail::query()
                        ->where(function ($q) use ($year) {
                            $q->where(function ($q2) use ($year) {
                                $q2->where(DB::raw('YEAR(start_datetime)'), '=', $year - 1)
                                    ->where(DB::raw('YEAR(end_datetime)'), '=', $year);
                            })
                                ->orWhere(function ($q2) use ($year) {
                                    $q2->where(DB::raw('YEAR(start_datetime)'), '=', $year)
                                        ->where(DB::raw('YEAR(end_datetime)'), '=', $year);
                                })
                                ->orWhere(function ($q2) use ($year) {
                                    $q2->where(DB::raw('YEAR(start_datetime)'), '=', $year)
                                        ->where(DB::raw('YEAR(end_datetime)'), '=', $year + 1);
                                });
                        })
                        ->where('user_id', '=', $value->user_id)
                        ->whereHas('overtime', function ($q) {
                            $q->where('approved', '=', 1)
                                ->whereNull('deleted_at');
                        });
                    if ($official_date->year == $year) {
                        $overtimes->whereDate(DB::raw('DATE(start_datetime)'), '>=', $official_date->toDateString());
                    }

                    $overtimes = $overtimes->get();;
                    $arrMonthOt = [];
                    foreach ($overtimes as $ovt) {
                        $startDateTimeOt = Carbon::createFromFormat('Y-m-d H:i:s', $ovt->start_datetime);
                        $endDateTimeOt = Carbon::createFromFormat('Y-m-d H:i:s', $ovt->end_datetime);
                        if ($startDateTimeOt->gt($endDateTimeOt)) continue;
                        $timeOt = 0;
                        if (!$startDateTimeOt->diffInDays($endDateTimeOt)) {
                            $timeOt = self::calculatingWorkingTimeOtInDay($startDateTimeOt->toTimeString(), $endDateTimeOt->toTimeString());
                            if (array_key_exists($startDateTimeOt->month, $arrMonthOt)) {
                                $arrMonthOt[$startDateTimeOt->month] = $arrMonthOt[$startDateTimeOt->month] + $timeOt;
                            } else {
                                $arrMonthOt[$startDateTimeOt->month] = $timeOt;
                            }
                        } else {
                            $count = 0;
                            for ($dateOt = $startDateTimeOt->copy(); $dateOt->diffInDays($endDateTimeOt, false) >= 0; $dateOt->addDay()) {
                                if ($dateOt->year !== $year) {
                                    continue;
                                }
                                $count++;
                                if ($dateOt->diffInDays($startDateTimeOt) === 0) {
                                    $timeOt = self::calculatingWorkingTimeOtInDay($startDateTimeOt->toTimeString(), '23:59:59');
                                } else {
                                    if ($dateOt->diffInDays($endDateTimeOt) === 0) {
                                        $timeOt = self::calculatingWorkingTimeOtInDay($endDateTimeOt->toTimeString(), '23:59:59');
                                    } else {
                                        $timeOt = 24 * 60 - 150;
                                    }
                                }
                                if (array_key_exists($dateOt->month, $arrMonthOt)) {
                                    $arrMonthOt[$dateOt->month] = $arrMonthOt[$dateOt->month] + $timeOt;
                                } else {
                                    $arrMonthOt[$dateOt->month] = $timeOt;
                                }
                            }
                        }
                    }
                    foreach ($arrMonthOt as $key => $v) {
                        $addOt = new TimeOnAdditionDayOff();
                        $addOt->time_on_accumulated_year_id = $currentAccumulatedYear->id;
                        $addOt->time = round($v / 60 / 8, 1);
                        $addOt->type = TIME_ON_ADDITION_DAY_OFF_TYPE_OT;
                        $addOt->manual_type = TIME_ON_ADDITION_DAY_OFF_MANUAL_TYPE_OT;
                        $addOt->status = TIME_ON_ADDITION_DAY_OFF_STATUS_NOT_COUNT;
                        $addOt->month = $key;
                        $addOt->year = $year;
                        $addOt->status = TIME_ON_ADDITION_DAY_OFF_STATUS_COUNT;
                        $addOt->save();
                    }

                }
                $timeAdds = TimeOnAdditionDayOff::query()
                    ->where('time_on_accumulated_year_id', '=', $currentAccumulatedYear->id)
                    ->where(function ($q) {
                        $q->where('status', '=', TIME_ON_ADDITION_DAY_OFF_STATUS_COUNT)
                            ->orWhere('status', '=', TIME_ON_ADDITION_DAY_OFF_STATUS_NOT_COUNT);
                    })
                    ->where('year', '=', $year)
                    ->get();
                $day_off_permit_in_year = 0;
                $day_off_ot_in_year = 0;
                foreach ($timeAdds as $v) {
                    if ($v->manual_type === TIME_ON_ADDITION_DAY_OFF_MANUAL_TYPE_PERMIT) {
                        $day_off_permit_in_year = $day_off_permit_in_year + $v->time;
                    }
                    if ($v->manual_type === TIME_ON_ADDITION_DAY_OFF_MANUAL_TYPE_OT) {
                        $day_off_ot_in_year = $day_off_ot_in_year + $v->time;
                    }
                }

                if ($day_off_permit_in_year < 0) {
                    $day_off_permit_in_year = 0;
                }
                if ($day_off_ot_in_year < 0) {
                    $day_off_ot_in_year = 0;
                }
                $currentAccumulatedYear->day_off_permit_in_year = $day_off_permit_in_year;
                $currentAccumulatedYear->day_off_ot_in_year = $day_off_ot_in_year;
                $currentAccumulatedYear->day_off_remain_permit = $currentAccumulatedYear->day_off_permit_in_year + $currentAccumulatedYear->day_off_accumulated_permit_previous_year;
                $currentAccumulatedYear->day_off_remain_ot = $currentAccumulatedYear->day_off_ot_in_year + $currentAccumulatedYear->day_off_accumulated_ot_previous_year;
                if (sizeof($arrMonths)) {
                    $currentAccumulatedYear->from_month = $arrMonths[0];
                    $currentAccumulatedYear->end_month = $arrMonths[sizeof($arrMonths) - 1];
                }
                $currentAccumulatedYear->save();

            }
        }
    }

    public static function getTimeResetDayoff($year)
    {
        $setting = Setting::query()
            ->first();
        $time_reset = DEFAULT_DAY_OFF_RESET_TIME;

        if ($setting !== null) {
            $time_reset = $setting->time_off_reset_milestone;
        }
        $date_reset = Carbon::createFromFormat('Y-d-m H:i:s', $year . '-' . $time_reset . ' 23:59:59');
        return $date_reset;
    }

    public static function calculatingDayOffWorkingMonth(Carbon $official_date)
    {

    }

    public static function calculatingWorkingTimeLeaveOutInDay($start_time, $end_time)
    {
        $end_morning = self::$endMorning;
        $start_afternoon = self::$startAfternoon;
        $end_morning = Carbon::createFromFormat('H:i:s', $end_morning);
        $start_afternoon = Carbon::createFromFormat('H:i:s', $start_afternoon);
        $start_time = Carbon::createFromFormat('H:i:s', $start_time);
        $end_time = Carbon::createFromFormat('H:i:s', $end_time);
        $time = 0;
        if ($start_time->lt($end_morning)) {
            if ($end_time->lte($end_morning)) {
                $time = $end_time->diffInMinutes($start_time);
            }
            if ($end_time->gt($end_morning) && $end_time->lt($start_afternoon)) {
                $time = $end_morning->diffInMinutes($start_time);
            }
            if ($end_time->gte($start_afternoon)) {
                $time = $end_time->diffInMinutes($start_time) - 90;
            }
        }
        if ($start_time->gte($end_morning)) {
            if ($end_time->gt($start_afternoon)) {
                $time = $end_time->diffInMinutes($start_afternoon);
            }
        }
        if ($start_time->gt($start_afternoon)) {
            $time = $end_time->diffInMinutes($start_time);
        }
        return $time;
    }

    public static function calculatingWorkingTimeLateInDay($check_in)
    {
        $start_morning = self::$startMorning;
        $end_morning = self::$endMorning;
        $start_afternoon = self::$startAfternoon;
        $end_afternoon = self::$endAfternoon;
        $start_morning = Carbon::createFromFormat('H:i:s', $start_morning);
        $end_morning = Carbon::createFromFormat('H:i:s', $end_morning);
        $start_afternoon = Carbon::createFromFormat('H:i:s', $start_afternoon);
        $end_afternoon = Carbon::createFromFormat('H:i:s', $end_afternoon);
        $check_in = Carbon::createFromFormat('H:i:s', $check_in);
        $time = 0;
        if ($check_in->lt($start_morning)) {
            return $time;
        }
        if ($check_in->lte($end_morning)) {
            $time = $check_in->diffInMinutes($start_morning);
        }
        if ($check_in->gt($end_morning) && $check_in->lte($start_afternoon)) {
            $time = $end_morning->diffInMinutes($start_morning);
        }
        if ($check_in->gt($start_afternoon) && $check_in->lte($end_afternoon)) {
            $time = $check_in->diffInMinutes($start_morning) - 90;
        }
        if ($check_in->gt($end_afternoon)) {
            $time = 480;
        }
        return $time;
    }

    public static function calculatingWorkingTimeLeaveEarlyInDay($check_out)
    {
        $start_morning = self::$startMorning;
        $end_morning = self::$endMorning;
        $start_afternoon = self::$startAfternoon;
        $end_afternoon = self::$endAfternoon;
        $start_morning = Carbon::createFromFormat('H:i:s', $start_morning);
        $end_morning = Carbon::createFromFormat('H:i:s', $end_morning);
        $start_afternoon = Carbon::createFromFormat('H:i:s', $start_afternoon);
        $end_afternoon = Carbon::createFromFormat('H:i:s', $end_afternoon);
        $check_out = Carbon::createFromFormat('H:i:s', $check_out);
        $time = 0;
        if ($check_out->gt($end_afternoon)) {
            return $time;
        }
        if ($check_out->gte($start_afternoon)) {
            $time = $check_out->diffInMinutes($end_afternoon);
        }
        if ($check_out->lt($start_afternoon) && $check_out->gte($end_morning)) {
            $time = $start_afternoon->diffInMinutes($end_afternoon);
        }
        if ($check_out->lt($end_morning) && $check_out->gte($start_morning)) {
            $time = $check_out->diffInMinutes($end_afternoon) - 90;
        }
        if ($check_out->lt($start_morning)) {
            $time = 480;
        }
        return $time;
    }


    public static function calculatingWorkingTimeOtInDay($start_time, $end_time)
    {
        $start_noon = '11:30:00';
        $end_noon = '13:00:00';
        $start_evening = '17:30:00';
        $end_evening = '18:30:00';

        $start_noon = Carbon::createFromFormat('H:i:s', $start_noon);
        $end_noon = Carbon::createFromFormat('H:i:s', $end_noon);
        $start_evening = Carbon::createFromFormat('H:i:s', $start_evening);
        $end_evening = Carbon::createFromFormat('H:i:s', $end_evening);
        $start_time = Carbon::createFromFormat('H:i:s', $start_time);
        $end_time = Carbon::createFromFormat('H:i:s', $end_time);
        $time = 0;
        if ($start_time->lt($start_noon)) {
            if ($end_time->lte($start_noon)) {
                $time = $end_time->diffInMinutes($start_time);
            }
            if ($end_time->gt($start_noon) && $end_time->lt($end_noon)) {
                $time = $start_noon->diffInMinutes($start_time);
            }
            if ($end_time->gte($end_noon) && $end_time->lte($start_evening)) {
                $time = $end_time->diffInMinutes($start_time) - 90;
            }
            if ($end_time->gt($start_evening) && $end_time->lt($end_evening)) {
                $time = $start_evening->diffInMinutes($start_time) - 90;
            }
            if ($end_time->gte($end_evening)) {
                $time = $end_time->diffInMinutes($start_time) - 150;
            }
        }
        if ($start_time->gte($start_noon) && $start_time->lt($end_noon)) {
            if ($end_time->gt($end_noon) && $end_time->lte($start_evening)) {
                $time = $end_time->diffInMinutes($end_noon);
            }
            if ($end_time->gt($start_evening) && $end_time->lte($end_evening)) {
                $time = $start_evening->diffInMinutes($end_noon);
            }
            if ($end_time->gt($end_evening)) {
                $time = $end_time->diffInMinutes($end_noon) - 60;
            }
        }
        if ($start_time->gte($end_noon) && $start_time->lte($start_evening)) {
            if ($end_time->gt($end_noon) && $end_time->lte($start_evening)) {
                $time = $end_time->diffInMinutes($start_time);
            }
            if ($end_time->gt($start_evening) && $end_time->lte($end_evening)) {
                $time = $start_evening->diffInMinutes($start_time);
            }
            if ($end_time->gt($end_evening)) {
                $time = $end_time->diffInMinutes($start_time) - 60;
            }
        }
        if ($start_time->gt($start_evening) && $start_time->lt($end_evening)) {
            if ($end_time->gt($end_evening)) {
                $time = $end_time->diffInMinutes($end_evening);
            }
        }
        if ($start_time->gte($end_evening)) {
            if ($end_time->gt($end_evening)) {
                $time = $end_time->diffInMinutes($start_time);
            }
        }
        return $time;
    }
}