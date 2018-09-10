<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //
    protected $table = 'setting';
    protected $fillable = [
        'start_morning',
        'end_morning',
        'start_afternoon',
        'end_afternoon',
        'in_late_threshold',
        'time_off_registration_threshold',
        'hr_and_administration_mail',
        'bom_mail',
        'time_off_reset_milestone'
    ];
    public $timestamps = null;
}
