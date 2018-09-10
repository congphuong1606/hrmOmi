<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OverTimeDetail extends Model
{
    //
    protected $table  = 'over_time_details';
    protected $fillable  = [
        'over_time_id','user_id','start_datetime','end_datetime','content'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function overtime() {
        return $this->belongsTo('App\Models\OverTime', 'over_time_id', 'id');
    }
}
