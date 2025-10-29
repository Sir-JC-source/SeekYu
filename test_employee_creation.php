<?php

require_once 'vendor/autoload.php';

use App\Models\RegisteredUsers;
use Illuminate\Support\Facades\Hash;

$user = new RegisteredUsers();
$user->login_id = 'EMP001';
$user->password = Hash::make('TestPass123');
$user->email = 'test.employee@example.com';
$user->fullname = 'Test Employee';
$user->role = 'security-guard';
$user->account_status = 'active';
$user->save();

echo 'Test employee created successfully. Login ID: EMP001, Password: TestPass123';
