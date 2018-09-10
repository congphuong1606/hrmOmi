<?php
/**
 * Created by PhpStorm.
 * User: devpc
 * Date: 9/19/2017
 * Time: 4:57 PM
 */

namespace App\Validators;


use App\Models\Employee;
use App\Models\User;

class EmployeeValidator
{
    public function ruleUniqueEmail($attribute, $value, $parameters, $validator) {
        if ($value === null) return true;
        $isExistEmployeeEmail = User::where('email', 'like', $value)->first();
        if ($isExistEmployeeEmail !== null) return false;
        return true;
    }

    public function messageUniqueEmail($attribute, $value, $parameters, $validator) {
        return "$attribute đã tồn tại";
    }

    public function ruleUniqueEmployeeCode($attribute, $value, $parameters, $validator) {
        if ($value === null) return true;
        $isExistEmployeeCode = Employee::where('employee_code', 'like', $value)->first();
        if ($isExistEmployeeCode !== null) return false;
        return true;
    }

    public function messageUniqueEmployeeCode($attribute, $value, $parameters, $validator) {
        return "$attribute đã tồn tại";
    }

    public function ruleUniqueAttendanceCode($attribute, $value, $parameters, $validator) {
        if ($value === null) return true;
        $isExistAttendanceCode = Employee::where('attendance_code', 'like', $value)->first();
        if ($isExistAttendanceCode !== null) return false;
        return true;
    }

    public function messageUniqueAttendanceCode($attribute, $value, $parameters, $validator) {
        return "$attribute đã tồn tại";
    }
}