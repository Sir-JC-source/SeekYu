<?php

require_once 'vendor/autoload.php';

use App\Models\JobApplication;
use App\Http\Controllers\GamificationController;

$applications = JobApplication::where('status', 'shortlisted')->with('user')->get();

foreach ($applications as $app) {
    if ($app->user) {
        $gamification = new GamificationController();
        $gamification->awardShortlistPoints($app->user->id);
        echo 'Awarded points to user: ' . $app->user->fullname . PHP_EOL;
    }
}

echo 'Points awarded to all shortlisted applicants.' . PHP_EOL;
