<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecializedSkill extends Model
{
    //
    protected $table = 'specialized_skills';

    protected $fillable = [
        'name', 'description'
    ];
}
