<?php
/**
 * Created by PhpStorm.
 * User: DatPA
 * Date: 2/5/2018
 * Time: 2:39 PM
 */

namespace App\Models;


use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $fillable = [
        'name','display_name','description'
    ];

//    public function screen(){
//        return $this->hasOne(Screen::class,'id','name');
//    }
}