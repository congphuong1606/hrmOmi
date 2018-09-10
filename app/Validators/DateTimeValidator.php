<?php
/**
 * Created by PhpStorm.
 * User: devpc
 * Date: 9/19/2017
 * Time: 4:57 PM
 */

namespace App\Validators;


class DateTimeValidator
{
    public function ruleDate($attribute, $value, $parameters, $validator) {
        $date = explode('-', $value);
        if (sizeof($date) != 3) return false;
        if (!is_numeric($date[0]) || !is_numeric($date[1]) || !is_numeric($date[2])) return false;
        $year = (int)$date[0];
        $month = (int)$date[1];
        $day = (int)$date[2];
        if ($year < 1900 || $year > 2030) return false;
        if ($month < 1 || $month > 12) return false;
        if ($day < 1 || $day > 31) return false;
        if ($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12) {
            if ($day > 31) return false;
        }
        if ($month == 4 || $month == 6 || $month == 9 || $month == 11) {
            if ($day > 30) return false;
        }
        if ($year % 4 == 0) {
            if ($month == 2 && $day > 29) return false;
        } else {
            if ($month == 2 && $day > 28) return false;
        };

        return true;
    }

    public function messageDate($attribute, $value, $parameters, $validator) {
        return 'date không đúng';
    }

    public function ruleTime($attribute, $value, $parameters, $validator) {
        $time = explode(':', $value);
        if (sizeof($time) != 3) return false;
        if (!is_numeric($time[0]) || !is_numeric($time[1]) || !is_numeric($time[2])) return false;
        $hours = (int)$time[0];
        $minute = (int)$time[1];
        $second = (int)$time[2];
        if ($hours < 0 || $minute < 0 || $second < 0) return false;
        if ($hours > 23 || $minute > 59 || $second > 59) return false;
        return true;
    }

    public function messageTime($attribute, $value, $parameters, $validator) {
        return 'time không đúng';
    }
}