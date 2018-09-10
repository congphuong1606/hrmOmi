<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeExcelDepartment extends Model
{
    //
    protected $table = 'employee_excel_department';

    public function department() {
        return $this->belongsTo('App\Models\Department', 'department_id', 'id');
    }

}
