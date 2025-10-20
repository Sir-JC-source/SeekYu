<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RegisteredUsers;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class FiveDigitUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure roles exist
        $roles = ['admin', 'hr-officer', 'security-guard', 'head-guard', 'client', 'applicant'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Create users
        $users = [
            ['fullname' => 'Admin User',     'login_id' => '00001', 'role' => 'admin'],
            ['fullname' => 'HR Officer',     'login_id' => '00002', 'role' => 'hr-officer'],
            ['fullname' => 'Security Guard', 'login_id' => '00003', 'role' => 'security-guard'],
            ['fullname' => 'Head Guard',     'login_id' => '00004', 'role' => 'head-guard'],
            ['fullname' => 'Client User',    'login_id' => '00005', 'role' => 'client'],
            ['fullname' => 'Applicant User', 'login_id' => '00006', 'role' => 'applicant'],
        ];

        foreach ($users as $userData) {
            $email = strtolower(str_replace(' ', '', $userData['fullname'])) . '@example.com';

            $user = RegisteredUsers::firstOrCreate(
                ['login_id' => $userData['login_id']],
                [
                    'fullname'       => $userData['fullname'],
                    'email'          => $email,
                    'password'       => Hash::make('password123'), // default password
                    'role'           => $userData['role'],
                    'account_status' => 'Approved',
                    'first_login'    => true,
                ]
            );

            // Assign role only if not already assigned
            if (!$user->hasRole($userData['role'])) {
                $user->assignRole($userData['role']);
            }
        }
    }
}
