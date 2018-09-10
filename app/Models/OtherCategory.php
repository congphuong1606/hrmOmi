<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherCategory extends Model
{
    //
    protected $fillable = [
        'name', 'description', 'type'
    ];
    protected $table ='other_categories';
}
