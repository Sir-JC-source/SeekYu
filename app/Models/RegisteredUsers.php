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
        'last_login',
        'leave_credits',
        'points',
        'level',
        'badges',
        'province',
        'city',
        'barangay',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'first_login' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'last_login' => 'datetime',
        'email_verified_at' => 'datetime',
        'points' => 'integer',
        'level' => 'integer',
        'badges' => 'array',
    ];

    /**
     * 🔗 Relationship: A registered user may have one employee record.
     */
    public function employee()
    {
        return $this->hasOne(Employee::class, 'employee_number', 'login_id');
    }

    /**
     * Relationship: job applications by this user.
     */
    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class, 'user_id');
    }

    /**
     * Get the count of applications submitted by this user.
     */
    public function getApplicationsCountAttribute()
    {
        return $this->jobApplications()->count();
    }

    /**
     * Relationship: applicant credentials for this user.
     */
    public function applicantCredential()
    {
        return $this->hasOne(ApplicantCredential::class, 'user_id');
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
