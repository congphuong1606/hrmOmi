<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class EmployeeLateReason extends Model
{
    protected $table = 'employee_late_reason';

    public function late_reason() {
        return $this->belongsTo('App\Models\LateReason', 'late_reason_id', 'id');
    }

}
