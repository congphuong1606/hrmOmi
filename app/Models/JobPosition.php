<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    //
    protected $table = 'job_positions';

    protected $fillable = [
        'name', 'description'
    ];

    public function specializedSkills()
    {
        return $this->belongsToMany(SpecializedSkill::class, 'job_status_specialized_skill', 'job_position_id','specialized_skill_id');
    }

}
