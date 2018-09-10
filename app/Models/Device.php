<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    //
    protected $table = 'devices';
    protected $fillable = [
        'email','fcm_device_token','type'
    ];
}
