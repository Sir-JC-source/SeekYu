<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RegisteredUsers;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Ensure the 'admin' role exists
        $role = Role::firstOrCreate(['name' => 'admin']);

        // Create the admin account
        $user = RegisteredUsers::firstOrCreate(
            ['login_id' => '00001'], // 5-digit login ID
            [
                'fullname'       => 'Administrator',
                'email'          => 'admin@example.com', // optional email
                'password'       => Hash::make('Admin1234'), // default password
                'role'           => 'admin',
                'account_status' => 'Approved',
                'first_login'    => true,
            ]
        );

        // Assign the role
        $user->assignRole($role);

        echo "Admin account created: Login ID 00001, Password Admin1234\n";
    }
}
