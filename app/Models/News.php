<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    //
    protected $table = 'news';
    protected $fillable = [
        'title', 'link', 'description', 'scope'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class,'news_user','news_id','user_id');
    }
}
