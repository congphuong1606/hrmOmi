<?php
/**
 * Created by PhpStorm.
 * User: devpc
 * Date: 9/19/2017
 * Time: 4:57 PM
 */

namespace App\Validators;


use App\Models\Department;
use App\Models\Employee;
use App\Models\JobStatus;
use App\Models\Position;
use App\Models\User;

class JobStatusValidator
{
    public function ruleExistJobStatusId($attribute, $value, $parameters, $validator) {
        $isExistJobStatusId = JobStatus::where('id', '=', $value)->first();
        if ($isExistJobStatusId === null) return false;
        return true;
    }

    public function messageExistJobStatusId($attribute, $value, $parameters, $validator) {
        return "$attribute đã tồn tại";
    }

}