<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeesAttachFiles extends Model
{
    //
    protected $table = 'employees_attach_files';

    protected $fillable = [
        'description', 'name', 'employee_id'
    ];

    public function jobStatus()
    {
        return $this->hasOne(JobStatus::class,'id','job_status_id');
    }
}
