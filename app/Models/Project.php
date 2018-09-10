<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //
    protected $table = 'projects';
    protected $fillable = [
        'project_code','name','start_date','end_date','status','type'
    ];
}
