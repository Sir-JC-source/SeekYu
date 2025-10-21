<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_number',
        'full_name',
        'position',
        'date_hired',
        'status',
        'employee_image',
        'contact_no',
        'province',
        'city',
        'shift_in',
        'shift_out',
        'designation',
        'assigned_head_guard_id',
        'deployment_status',
    ];

    /**
     * Accessor: Get the full image URL or a default avatar.
     */
    public function getImageUrlAttribute()
    {
        return $this->employee_image 
            ? asset('storage/' . $this->employee_image) 
            : asset('assets/default-avatar.png');
    }

    /**
     * Relationship: Employee belongs to a Registered User.
     */
    public function registeredUser()
    {
        return $this->belongsTo(RegisteredUsers::class, 'employee_number', 'login_id');
    }

    /**
     * Relationship: Assigned Head Guard.
     */
    public function assignedHeadGuard()
    {
        return $this->belongsTo(Employee::class, 'assigned_head_guard_id');
    }

    /**
     * 🔥 Automatically create an Employee record from a Registered User.
     */
    public static function createFromUser($user)
    {
        // Skip if already exists
        if (self::where('employee_number', $user->login_id)->exists()) {
            return;
        }

        // Define readable position based on role
        $position = match ($user->role) {
            'super-admin'    => 'Super Administrator',
            'admin'          => 'Administrator',
            'hr-officer'     => 'HR Officer',
            'head-guard'     => 'Head Guard',
            'security-guard' => 'Security Guard',
            default          => ucfirst(str_replace('-', ' ', $user->role ?? 'Employee')),
        };

        // Create employee record
        self::create([
            'employee_number'        => $user->login_id ?? str_pad($user->id, 5, '0', STR_PAD_LEFT),
            'full_name'              => $user->fullname ?? 'N/A',
            'position'               => $position,
            'date_hired'             => now(),
            'status'                 => 'Active',
            'designation'            => $user->role ?? 'employee',
            'deployment_status'      => 'assigned',
            'employee_image'         => $user->profile_picture ?? null,
            'contact_no'             => $user->contact_no ?? '0000000000', // ✅ Ensures non-null
            'province'               => $user->province ?? 'N/A',
            'city'                   => $user->city ?? 'N/A',
            'shift_in'               => '08:00:00',
            'shift_out'              => '17:00:00',
            'assigned_head_guard_id' => null,
        ]);
    }
}
