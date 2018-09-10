<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScreenCategory extends Model
{
    //
    protected $table = 'screen_categories';
    protected $fillable = [
        'id', 'name', 'display_name', 'description'
    ];

    /**
     * Screen relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function screens()
    {
        return $this->hasMany(Screen::class, 'screen_category_id', 'id');
    }
}
