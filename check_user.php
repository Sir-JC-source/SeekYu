<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\RegisteredUsers;

$user = RegisteredUsers::where('login_id', '20250001')->first();

if ($user) {
    echo 'User found: ' . $user->fullname . PHP_EOL;
    echo 'Role: ' . $user->role . PHP_EOL;
    echo 'Status: ' . $user->account_status . PHP_EOL;
    echo 'Email verified: ' . ($user->email_verified_at ? 'Yes' : 'No') . PHP_EOL;
    echo 'Has role assigned: ' . ($user->hasRole($user->role) ? 'Yes' : 'No') . PHP_EOL;
} else {
    echo 'User not found' . PHP_EOL;
}
