<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeExcelFile extends Model
{
    //
    protected $table = 'employee_excel_file';

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function data() {
        return $this->hasMany('App\Models\EmployeeExcelData', 'employee_excel_file_id', 'id');
    }

}
