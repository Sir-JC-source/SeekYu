<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deployment extends Model
{
    protected $fillable = [
        'employee_id',
        'deployment_date',
        'shift_in',
        'shift_out',
        'assigned_head_guard_id',
        'status',
        'created_by'
    ];

    protected $casts = [
        'deployment_date' => 'date',
        'shift_in' => 'datetime:H:i',
        'shift_out' => 'datetime:H:i',
    ];

    // Relationships
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function headGuard(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_head_guard_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('deployment_date', $date);
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    // Helper methods
    public function isConflicting($employeeId, $date, $shiftIn, $shiftOut)
    {
        return static::where('employee_id', $employeeId)
            ->where('deployment_date', $date)
            ->where(function ($query) use ($shiftIn, $shiftOut) {
                $query->whereBetween('shift_in', [$shiftIn, $shiftOut])
                      ->orWhereBetween('shift_out', [$shiftIn, $shiftOut])
                      ->orWhere(function ($q) use ($shiftIn, $shiftOut) {
                          $q->where('shift_in', '<=', $shiftIn)
                            ->where('shift_out', '>=', $shiftOut);
                      });
            })
            ->exists();
    }
}
