<?php

require_once 'vendor/autoload.php';

use App\Models\RegisteredUsers;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

try {
    // Create user account
    $user = new RegisteredUsers();
    $user->login_id = 'EMP001';
    $user->password = Hash::make('TestPass123');
    $user->email = 'test.employee@example.com';
    $user->fullname = 'Test Employee';
    $user->role = 'security-guard';
    $user->account_status = 'active';
    $user->save();

    // Create employee record
    $employee = new Employee();
    $employee->user_id = $user->id;
    $employee->employee_number = 'EMP001';
    $employee->first_name = 'Test';
    $employee->middle_name = 'Middle';
    $employee->last_name = 'Employee';
    $employee->age = 25;
    $employee->province = 'Metro Manila (NCR)';
    $employee->city = 'Makati';
    $employee->barangay = 'Poblacion';
    $employee->date_hired = date('Y-m-d');
    $employee->position = 'Security Guard';
    $employee->save();

    echo "Test employee created successfully!\n";
    echo "Login ID: EMP001\n";
    echo "Password: TestPass123\n";
    echo "Email: test.employee@example.com\n";

} catch (Exception $e) {
    echo "Error creating test employee: " . $e->getMessage() . "\n";
}
