<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeExcelJobStatus extends Model
{
    //
    protected $table = 'employee_excel_job_status';

    public function job_status() {
        return $this->belongsTo('App\Models\JobStatus', 'job_status_id', 'id');
    }

}
