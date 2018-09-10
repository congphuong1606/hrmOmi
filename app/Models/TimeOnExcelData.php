<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeOnExcelData extends Model
{
    //
    protected $table = 'time_on_excel_data';

    public function employee() {
        return $this->belongsTo('App\Models\Employee', 'employee_id', 'id');
    }

    public function time_on() {
        return $this->belongsTo('App\Models\TimeOn', 'time_on_id', 'id');
    }

}
