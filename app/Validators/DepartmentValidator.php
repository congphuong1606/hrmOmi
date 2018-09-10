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
use App\Models\User;

class DepartmentValidator
{
    public function ruleExistDepartmentId($attribute, $value, $parameters, $validator) {
        $isExistDepartmentId = Department::where('id', '=', $value)->first();
        if ($isExistDepartmentId === null) return false;
        return true;
    }

    public function messageExistDepartmentId($attribute, $value, $parameters, $validator) {
        return "$attribute đã tồn tại";
    }

}