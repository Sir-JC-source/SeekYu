<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class RegisteredUsers extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'registered_users';

    protected $fillable = [
        'fullname',
        'student_no',
        'faculty_no',
        'login_id',
        'email',
        'address',
        'password',
        'role',
        'account_status',
        'profile_picture',
        'status',
        'first_login',
        'contact_no', // ✅ added
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'first_login' => 'boolean',
    ];

    /**
     * 🔗 Relationship: A registered user may have one employee record.
     */
    public function employee()
    {
        return $this->hasOne(Employee::class, 'employee_number', 'login_id');
    }

    /**
     * 🧠 Automatically create an Employee record when a new internal user is created.
     */
    protected static function booted()
    {
        static::created(function ($user) {
            $employeeRoles = ['super-admin', 'admin', 'hr-officer', 'head-guard', 'security-guard'];

            if (in_array($user->role, $employeeRoles)) {
                \App\Models\Employee::createFromUser($user);
            }
        });
    }
}
