<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    //
    protected $table = 'training';
    protected $fillable = [
        'course_id', 'user_id', 'score'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
