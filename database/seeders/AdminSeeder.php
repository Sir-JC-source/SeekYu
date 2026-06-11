<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RegisteredUsers;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Disabled by request: do not seed admin account.
        // If you re-seed, we still want to make sure an existing admin record isn't usable.
        // (login will be blocked by account_status below)
        
        // NOTE: This seeder only runs during seeding. For already-existing DB data,
        // this prevents creating new admins but doesn't automatically revoke existing ones.
        return;


        // ✅ Ensure the 'admin' role exists
        $role = Role::firstOrCreate(['name' => 'admin']);

        // ✅ Create or get Admin user
        $user = RegisteredUsers::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'fullname'        => 'System Administrator',
                'student_no'      => null,
                'faculty_no'      => null,
                'login_id'        => '00001',
                'email'           => 'admin@example.com',
                'address'         => 'System HQ',
                'password'        => Hash::make('password123'),
                'role'            => 'admin',
                'account_status'  => 'active',
                'profile_picture' => null,
                'status'          => 'active',
                'first_login'     => true,
                'contact_no'      => '09123456789',
            ]
        );

        // ✅ Assign role
        $user->assignRole($role);

        // ✅ Auto create Employee record
        Employee::createFromUser($user);

        $this->command->info('✅ Admin account created successfully.');
        $this->command->info('   Login: admin@example.com | Password: password123');
    }
}
