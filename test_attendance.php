<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\Dashboard\DashboardController;

$controller = new DashboardController();
$result = $controller->calculateAttendanceTrends(6, 'monthly');

echo "Attendance Trends Result:\n";
print_r($result);
