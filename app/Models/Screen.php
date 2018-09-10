<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Screen extends Model
{
    //
    protected $table = 'screens';
    protected $fillable = [
        'name', 'display_name', 'description', 'screen_category_id','url'
    ];

    /**
     * Screen Category relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(ScreenCategory::class, 'screen_category_id', 'id');
    }
}
