<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeExcelData extends Model
{
    //
    protected $table = 'employee_excel_data';

    public function employee() {
        return $this->belongsTo('\App\Models\Employee', 'employee_id', 'id');
    }

}
