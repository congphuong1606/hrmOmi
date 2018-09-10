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
use App\Models\WorkingStatus;

class WorkingStatusValidator
{
    public function ruleExistWorkingStatusId($attribute, $value, $parameters, $validator) {
        $isExistWorkingStatusId = WorkingStatus::where('id', '=', $value)->first();
        if ($isExistWorkingStatusId === null) return false;
        return true;
    }

    public function messageExistWorkingStatusId($attribute, $value, $parameters, $validator) {
        return "$attribute đã tồn tại";
    }

}