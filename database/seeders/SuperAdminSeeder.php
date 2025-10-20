<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RegisteredUsers;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Ensure roles exist
        $role = Role::firstOrCreate(['name' => 'super-admin']);

        // Create Super Admin User
        $user = RegisteredUsers::firstOrCreate(
            ['email' => 'superadmin@example.com'], // check if already exists
            [
                'fullname'        => 'Super Administrator',
                'student_no'      => null, // not needed for admin
                'faculty_no'      => null, // not needed for admin
                'email'           => 'superadmin@example.com',
                'address'         => 'System Address',
                'password'        => Hash::make('password123'), // change later
                'role'            => 'super-admin',
                'account_status'  => 'active',
                'profile_picture' => null,
                'status'          => 'active',
                'first_login'     => true,
            ]
        );

        // Assign role
        $user->assignRole($role);
    }
}
