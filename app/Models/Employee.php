<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $table = 'employees';

    protected $fillable = [
        'user_id', 'full_name', 'department_id', 'position_id', 'late_reason_id',
        'job_status_id', 'working_status_id', 'birth_day', 'identification_number', 'identification_date',
        'identification_place_of', 'tax_code', 'permanent_address',
        'temporary_address', 'bank_number', 'bank_name', 'phone_number', 'bank_user_name', 'bank_branch',
        'chatwork_account', 'skype_account', 'facebook_link', 'employee_code', 'attendance_code', 'personal_email',
        'email', 'update_date', 'check_in_date', 'training_date', 'official_date', 'contact_user'
    ];

    /**
     * User relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

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

    /**
     * Job Status History relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jobStatusHistory()
    {
        return $this->belongsTo(JobStatusUpdateHistory::class, 'id', 'employee_id');
    }

    public function getAvatarAttribute()
    {
        $value = $this->attributes['avatar'];
        return $value === null ? DOMAIN  . 'img/no_account.png' : DOMAIN . EMPLOYEE_AVATAR_DIR . $value;
    }

    public function specialized_skills()
    {
        return $this->belongsToMany('App\Models\SpecializedSkill', 'employees_specialized_skills', 'employee_id', 'specialized_skill_id');
    }

    public function attach_files()
    {
        return $this->hasMany('App\Models\EmployeesAttachFiles', 'employee_id', 'id');
    }

    public function lateReason()
    {
        return $this->belongsTo(LateReason::class, 'late_reason_id', 'id');
    }

    public function directManager(){
        return $this->belongsTo(Employee::class,'direct_manager_id','id');
    }

    public function project_managers() {
        return $this->belongsToMany(Employee::class, 'employee_project_manager', 'employee_id', 'project_manager_id');
    }

    public function time_on_accumulated_years() {
        return $this->hasMany('App\Models\TimeOnAccumulatedYear', 'employee_id', 'id');
    }

    public function employee_late_reasons() {
        return $this->hasMany('App\Models\EmployeeLateReason', 'employee_id', 'id');
    }

    public function late_reasons() {
        return $this->belongsToMany('App\Models\LateReason', 'employee_late_reason', 'employee_id', 'late_reason_id');
    }
}