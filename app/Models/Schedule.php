<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = [
        'guard_id',
        'schedule_date',
        'shift_in',
        'shift_out',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'shift_in' => 'string',
        'shift_out' => 'string',
    ];

    /**
     * Get the guard (employee) that owns the schedule.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'guard_id');
    }

    /**
     * Get the user who created the schedule.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the schedule.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
