<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeOffFile extends Model
{
    //
    protected $table = 'time_off_excel_files';
    protected $fillable = [
        'name', 'user_id'
    ];
}
