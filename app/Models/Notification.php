<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    protected $table = 'notifications';
    protected $fillable = [
        'title', 'body', 'action', 'email', 'read'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function timeOff()
    {
        return $this->belongsTo(TimeOff::class, 'time_off_id', 'id');
    }

    public function overTime()
    {
        return $this->belongsTo(OverTime::class, 'over_time_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function training(){
        return $this->belongsTo(Training::class, 'training_id', 'id');
    }
}
