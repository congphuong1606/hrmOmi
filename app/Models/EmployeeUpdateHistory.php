<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeUpdateHistory extends Model
{
    //
    protected $table = 'employees_update_history';

    protected $fillable = [
        'employee_id', 'full_name', 'department_id', 'position_id', 'late_reason_id',
        'job_status_id', 'working_status_id', 'birth_day', 'identification_number', 'identification_date',
        'identification_place_of', 'tax_code', 'permanent_address',
        'temporary_address', 'bank_number', 'bank_name', 'phone_number', 'bank_user_name', 'bank_branch',
        'chatwork_account', 'skype_account', 'facebook_link', 'status', 'employee_code', 'attendance_code', 'personal_email',
        'email', 'update_date', 'check_in_date', 'training_date', 'official_date', 'contact_user'
    ];

    /**
     * Department relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function department()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    /**
     * Position relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function position()
    {
        return $this->hasOne(Position::class, 'id', 'position_id');
    }

    /**
     * Working Status relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function workingStatus()
    {
        return $this->hasOne(WorkingStatus::class, 'id', 'working_status_id');
    }

    /**
     * Job Status relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function jobStatus()
    {
        return $this->hasOne(JobStatus::class, 'id', 'job_status_id');
    }

    public function jobStatusHistory()
    {
        return $this->belongsTo(JobStatusUpdateHistory::class, 'id', 'employee_id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }

    public function getAvatarAttribute()
    {
        $value = $this->attributes['avatar'];
        return $value === null ? DOMAIN  . 'img/no_account.png' : DOMAIN . EMPLOYEE_AVATAR_DIR . $value;
    }

    public function setAvatarAttribute($value)
    {
        $this->attributes['avatar'] = $value;
    }

    public function lateReason()
    {
        return $this->belongsTo(LateReason::class, 'late_reason_id', 'id');
    }
}
