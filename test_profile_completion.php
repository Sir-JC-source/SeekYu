<?php

require_once 'vendor/autoload.php';

use App\Models\RegisteredUsers;
use App\Http\Controllers\GamificationController;

echo "Testing profile completion...\n";

$user = RegisteredUsers::whereNotNull('fullname')
    ->whereNotNull('contact_no')
    ->whereNotNull('province')
    ->whereNotNull('city')
    ->whereNotNull('barangay')
    ->first();

if ($user) {
    echo "Found user: " . $user->fullname . "\n";
    echo "Points before: " . $user->points . "\n";

    $gamification = new GamificationController();
    $gamification->awardProfileCompletionPoints($user->id);

    $user->refresh();
    echo "Points after: " . $user->points . "\n";
} else {
    echo "No user with complete profile found.\n";
}
