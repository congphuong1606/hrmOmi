<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeOnExcelFile extends Model
{
    //
    protected $table = 'time_on_excel_file';

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function data() {
        return $this->hasMany('App\Models\TimeOnExcelData', 'time_on_excel_file_id', 'id');
    }

}
