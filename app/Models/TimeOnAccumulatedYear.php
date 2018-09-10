<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeOnAccumulatedYear extends Model
{
    //
    protected $table = 'time_on_accumulated_year';

    function employee() {
        return $this->belongsTo('App\Models\Employee', 'employee_id', 'id');
    }

    function time_on_addition_day_offs() {
        return $this->hasMany('App\Models\TimeOnAdditionDayOff', 'time_on_accumulated_year_id', 'id');
    }

}
