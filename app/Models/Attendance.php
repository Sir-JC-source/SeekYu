<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'attendance_date',
        'shift_in_time',
        'shift_out_time',
        'total_hours',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'shift_in_time' => 'datetime:H:i',
        'shift_out_time' => 'datetime:H:i',
        'total_hours' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
