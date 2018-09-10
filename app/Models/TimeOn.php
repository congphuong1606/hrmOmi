<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeOn extends Model
{
    //
    protected $table = 'time_on';
    protected $fillable = [
        'employee_id', 'date', 'check_in', 'check_out', 'status'
    ];

    public function time_offs() {
        return $this->belongsToMany('App\Models\TimeOff', 'time_on_time_off', 'time_on_id', 'time_off_id')->withPivot('time');
    }

    public function over_times() {
        return $this->belongsToMany('App\Models\OverTimeDetail', 'time_on_over_time', 'time_on_id', 'over_time_detail_id')->withPivot('time');
    }

    public function employee() {
        return $this->belongsTo('App\Models\Employee', 'employee_id', 'id');
    }

    public function holidays() {
        return $this->belongsToMany('App\Models\OfficialHoliday', 'time_on_official_holiday', 'time_on_id', 'official_holiday_id')->withPivot('time');
    }

    public function time_on_month() {
        return $this->belongsTo('App\Models\TimeOnMonth', 'time_on_month_id', 'id');
    }

}
