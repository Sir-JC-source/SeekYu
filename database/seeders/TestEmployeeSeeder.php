<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RegisteredUsers;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class TestEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timestamp = now()->timestamp;
        $loginId = 'EMP' . $timestamp;
        $email = 'test.employee.' . $timestamp . '@example.com';

        // Create user account
        $user = RegisteredUsers::create([
            'login_id' => $loginId,
            'password' => Hash::make('TestPass123'),
            'email' => $email,
            'fullname' => 'Test Employee ' . $timestamp,
            'role' => 'security-guard',
            'account_status' => 'active',
        ]);

        // Create employee record
        Employee::create([
            'employee_number' => $user->login_id,
            'full_name' => $user->fullname,
            'position' => 'Security Guard',
            'date_hired' => now()->format('Y-m-d'),
            'status' => 'Active',
            'first_name' => 'Test',
            'middle_name' => 'Middle',
            'last_name' => 'Employee',
            'age' => 25,
            'province' => 'Metro Manila (NCR)',
            'city' => 'Makati',
            'barangay' => 'Poblacion',
            'email' => $email,
        ]);

        echo "Test employee created successfully!\n";
        echo "Login ID: $loginId\n";
        echo "Password: TestPass123\n";
        echo "Email: $email\n";
    }
}
