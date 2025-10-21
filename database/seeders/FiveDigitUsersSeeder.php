<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RegisteredUsers;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class FiveDigitUsersSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['role' => 'hr-officer', 'email' => 'hr@example.com', 'login_id' => '00002', 'name' => 'HR Officer'],
            ['role' => 'head-guard', 'email' => 'headguard@example.com', 'login_id' => '00003', 'name' => 'Head Guard'],
            ['role' => 'security-guard', 'email' => 'guard1@example.com', 'login_id' => '00004', 'name' => 'Security Guard 1'],
            ['role' => 'security-guard', 'email' => 'guard2@example.com', 'login_id' => '00005', 'name' => 'Security Guard 2'],
        ];

        foreach ($roles as $data) {
            // ✅ Ensure role exists
            $role = Role::firstOrCreate(['name' => $data['role']]);

            // ✅ Create or get user
            $user = RegisteredUsers::firstOrCreate(
                ['email' => $data['email']],
                [
                    'fullname'        => $data['name'],
                    'student_no'      => null,
                    'faculty_no'      => null,
                    'login_id'        => $data['login_id'],
                    'email'           => $data['email'],
                    'address'         => 'Assigned Location',
                    'password'        => Hash::make('password123'),
                    'role'            => $data['role'],
                    'account_status'  => 'active',
                    'profile_picture' => null,
                    'status'          => 'active',
                    'first_login'     => true,
                    'contact_no'      => '09' . rand(100000000, 999999999), // ✅ Random 11-digit
                ]
            );

            // ✅ Assign Role
            $user->assignRole($role);

            // ✅ Create Employee
            Employee::createFromUser($user);
        }

        $this->command->info('✅ Admin + 5-digit users created successfully.');
        $this->command->info('   All passwords: password123');
    }
}
