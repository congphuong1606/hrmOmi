<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
    protected $table = 'courses';
    protected $fillable = ['other_category_id', 'description', 'current_order'];

    public function sessions()
    {
        return $this->hasMany(Session::class, 'course_id', 'id');
    }

    /**
     * otherCategory relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function otherCategory()
    {
        return $this->belongsTo(OtherCategory::class, 'course_category_id', 'id');
    }

    /**
     * otherCategory relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function training()
    {
        return $this->hasMany(Training::class, 'course_id', 'id');
    }

    /**
     * courseScoreExcelFiles relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courseScoreExcelFiles()
    {
        return $this->hasMany(CourseScoreExcelFile::class, 'course_id', 'id');
    }
}
