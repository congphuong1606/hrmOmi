<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseScoreExcelFile extends Model
{
    //
    protected $table = 'course_score_excel_files';
    protected $fillable = [
        'course_id', 'name', 'user_id', 'status'
    ];
}
