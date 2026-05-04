<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'HR Officer',
                'email' => 'hr@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'hr-officer',
            ],
            [
                'name' => 'Head Guard',
                'email' => 'headguard@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'head-guard',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}

