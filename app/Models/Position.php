<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    //
    protected $table = 'positions';

    protected $fillable = [
        'code', 'name', 'description'
    ];

}
