<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Add SoftDeletes

class Employee extends Model
{
    use HasFactory, SoftDeletes; // Enable SoftDeletes

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_number',
        'full_name',
        'position',
        'date_hired',
        'status',
        'employee_image', // added for image upload
        'shift_in',
        'shift_out',
        'designation',
        'assigned_head_guard_id',
        'deployment_status',
    ];

    /**
     * Accessor to get full image URL if needed.
     */
    public function getImageUrlAttribute()
    {
        return $this->employee_image ? asset('storage/' . $this->employee_image) : asset('assets/default-avatar.png');
    }

    /**
     * Relationship to get the assigned Head Guard.
     */
    public function assignedHeadGuard()
    {
        return $this->belongsTo(Employee::class, 'assigned_head_guard_id');
    }
}
