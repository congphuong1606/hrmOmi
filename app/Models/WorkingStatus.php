<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingStatus extends Model
{
    //
    protected $table = 'working_status';

    protected $fillable = [
        'id', 'name', 'description','code'
    ];
}
