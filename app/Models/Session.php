<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    //
    protected $table = 'sessions';
    protected $fillable = [
        'course_id', 'start_datetime', 'end_datetime', 'trainer', 'supporter', 'content', 'room'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class,'session_user','session_id','user_id')->withPivot('presence');
    }

    public function room(){
        return $this->belongsTo(OtherCategory::class,'room_category_id','id');
    }
}
