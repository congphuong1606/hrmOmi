<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobStatusUpdateHistory extends Model
{
    //
    protected $table = 'employees_job_status_history';

    protected $fillable = [
        'employee_id', 'job_status_id'
    ];

    public function jobStatus()
    {
        return $this->hasOne(JobStatus::class,'id','job_status_id');
    }
}
