<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordForget extends Model
{
    //
    public $timestamps = null;
    protected $table = 'password_forgets';
    protected $fillable =  [
        'email',
        'verified_code'
    ];
    protected $primaryKey =  'email';
}
