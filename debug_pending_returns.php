<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DailyReturn;

echo "=== Debugging Pending Returns ===" . PHP_EOL;

$currentDate = now();
echo "Current Date: {$currentDate->format('Y-m-d H:i:s')}" . PHP_EOL;

// Check a few pending returns
$pendingReturns = DailyReturn::where('status', 'pending')
    ->with(['investment', 'user'])
    ->orderBy('return_date')
    ->limit(5)
    ->get();

echo PHP_EOL . "Sample pending returns:" . PHP_EOL;

foreach ($pendingReturns as $return) {
    echo "Return ID: {$return->id}" . PHP_EOL;
    echo "  User: {$return->user->name}" . PHP_EOL;
    echo "  Return Date: {$return->return_date->format('Y-m-d')}" . PHP_EOL;
    echo "  Investment ID: {$return->investment_id}" . PHP_EOL;
    echo "  Investment Status: {$return->investment->status}" . PHP_EOL;
    echo "  Is Active: " . ($return->investment->status === 'active' ? 'YES' : 'NO') . PHP_EOL;

    $isPastOrToday = $return->return_date <= $currentDate;
    echo "  Is Past/Today: " . ($isPastOrToday ? 'YES' : 'NO') . PHP_EOL;
    echo "---" . PHP_EOL;
}

// Check by date ranges
$pastReturns = DailyReturn::where('status', 'pending')
    ->where('return_date', '<', $currentDate->format('Y-m-d'))
    ->count();

$todayReturns = DailyReturn::where('status', 'pending')
    ->whereDate('return_date', $currentDate->format('Y-m-d'))
    ->count();

$futureReturns = DailyReturn::where('status', 'pending')
    ->where('return_date', '>', $currentDate->format('Y-m-d'))
    ->count();

echo PHP_EOL . "Pending returns by date:" . PHP_EOL;
echo "Past: {$pastReturns}" . PHP_EOL;
echo "Today: {$todayReturns}" . PHP_EOL;
echo "Future: {$futureReturns}" . PHP_EOL;
