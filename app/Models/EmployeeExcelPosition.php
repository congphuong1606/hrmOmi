<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeExcelPosition extends Model
{
    //
    protected $table = 'employee_excel_position';

    public function position() {
        return $this->belongsTo('App\Models\Position', 'position_id', 'id');
    }

}
