<?php
/**
 * Created by PhpStorm.
 * User: DatPA
 * Date: 2/5/2018
 * Time: 2:38 PM
 */

namespace App\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $fillable = [
        'name', 'display_name', 'description'
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    }

    public function screens()
    {
        return $this->belongsToMany(Screen::class, 'screen_role', 'role_id', 'screen_id');
    }
}