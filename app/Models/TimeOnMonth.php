<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeOnMonth extends Model
{
    //
    protected $table = 'time_on_month';

    public function employee() {
        return $this->belongsTo('App\Models\Employee', 'employee_id', 'id');
    }

    public function time_ons() {
        return $this->hasMany('App\Models\TimeOn', 'time_on_month_id', 'id');
    }

}
