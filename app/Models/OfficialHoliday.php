<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficialHoliday extends Model
{
    //
    protected $table = 'official_holidays';
    protected $fillable  = [
        'start_date','end_date','description'
    ];
}
