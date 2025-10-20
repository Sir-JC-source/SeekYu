<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class RegisteredUsers extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $table = 'registered_users';

    protected $fillable = [
        'fullname',
        'student_no',
        'email',
        'address',
        'password',
        'role',
        'account_status',
        'profile_picture',
        'status',
        'first_login',
    ];
}
