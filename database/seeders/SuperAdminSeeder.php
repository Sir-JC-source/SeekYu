<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RegisteredUsers;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // ✅ Ensure the role exists
        $role = Role::firstOrCreate(['name' => 'super-admin']);

        // ✅ Create or get the Super Admin user
        $user = RegisteredUsers::firstOrCreate(
            ['email' => 'superadmin@example.com'], // Unique check
            [
                'fullname'        => 'Super Administrator',
                'student_no'      => null,
                'faculty_no'      => null,
                'login_id'        => '00000', // optional fixed ID for consistency
                'email'           => 'superadmin@example.com',
                'address'         => 'System Address',
                'password'        => Hash::make('password123'),
                'role'            => 'super-admin',
                'account_status'  => 'active',
                'profile_picture' => null,
                'status'          => 'active',
                'first_login'     => true,
                'contact_no' => '0000000000',

            ]
        );

        // ✅ Assign the Super Admin role
        $user->assignRole($role);

        // ✅ Create Employee record (safe: won’t duplicate)
        Employee::createFromUser($user);

        // ✅ Console message for feedback
        $this->command->info('✅ Super Admin account created and added to Employees table.');
        $this->command->info('   Login: superadmin@example.com | Password: password123');
    }
}
