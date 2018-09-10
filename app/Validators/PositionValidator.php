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
use App\Models\Position;
use App\Models\User;

class PositionValidator
{
    public function ruleExistPositionId($attribute, $value, $parameters, $validator) {
        $isExistPositionId = Position::where('id', '=', $value)->first();
        if ($isExistPositionId === null) return false;
        return true;
    }

    public function messageExistPositionId($attribute, $value, $parameters, $validator) {
        return "$attribute đã tồn tại";
    }

}