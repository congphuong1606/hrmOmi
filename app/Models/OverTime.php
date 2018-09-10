<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OverTime extends Model
{
    //
    protected $table = 'over_times';
    protected $fillable = [
        'project_category_id',
        'proposer',
        'approved'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(OverTimeDetail::class, 'over_time_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function otherCategory()
    {
        return $this->belongsTo(OtherCategory::class, 'project_category_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'proposer', 'email');
    }
}
