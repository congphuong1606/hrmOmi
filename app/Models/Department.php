<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //
    protected $table = 'departments';

    protected $fillable = [
        'code', 'name', 'description'
    ];

    public function employees() {
        return $this->hasMany('App\Models\Employee', 'department_id', 'id');
    }
}
