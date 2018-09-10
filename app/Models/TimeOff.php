<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeOff extends Model
{
    //
    protected $table = 'time_off';
    protected $fillable = [
        'employee_id', 'start_datetime', 'end_datetime', 'status','reason',
        'detailed_reason','backup_person', 'approved_reason','approved','file_id',
        'flow_type','forget_type'
    ];

    /**
     * Employee relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
