<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeesSpecializedSkills extends Model
{
    //
    protected $table = 'employees_specialized_skills';

    protected $fillable = [
        'employee_id', 'specialized_skill_id'
    ];

    public function jobStatus()
    {
        return $this->hasOne(JobStatus::class,'id','job_status_id');
    }
}
