<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LateReason extends Model
{
    //
    protected $table = 'late_reasons';
    protected $fillable = [
        'name',
        'description',
        'start_morning',
        'end_morning',
        'start_afternoon',
        'end_afternoon'
    ];
}
