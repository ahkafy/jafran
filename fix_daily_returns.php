<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DailyReturn;
use App\Models\Investment;
use App\Services\MLMService;

echo "=== Fixing Daily Returns Data ===" . PHP_EOL;

// Delete all existing daily returns
$existingCount = DailyReturn::count();
echo "Deleting {$existingCount} existing daily returns..." . PHP_EOL;
DailyReturn::truncate();

echo "All daily returns deleted." . PHP_EOL;

// Regenerate daily returns for all investments
$investments = Investment::all();
$mlmService = app(MLMService::class);

foreach ($investments as $investment) {
    echo "Generating daily returns for Investment #{$investment->id}..." . PHP_EOL;
    echo "  Start Date: {$investment->start_date}" . PHP_EOL;
    echo "  End Date: {$investment->end_date}" . PHP_EOL;
    echo "  Return Days: {$investment->investmentPackage->return_days}" . PHP_EOL;

    $mlmService->generateDailyReturns($investment);

    $generatedCount = $investment->dailyReturns()->count();
    echo "  Generated {$generatedCount} daily returns." . PHP_EOL;

    // Show first and last return dates
    $firstReturn = $investment->dailyReturns()->orderBy('return_date')->first();
    $lastReturn = $investment->dailyReturns()->orderBy('return_date', 'desc')->first();

    if ($firstReturn && $lastReturn) {
        echo "  First return: {$firstReturn->return_date->format('Y-m-d')} (Day {$firstReturn->day_number})" . PHP_EOL;
        echo "  Last return: {$lastReturn->return_date->format('Y-m-d')} (Day {$lastReturn->day_number})" . PHP_EOL;
    }

    echo "---" . PHP_EOL;
}

// Check current status
$currentDate = now()->format('Y-m-d');
echo PHP_EOL . "=== Current Status ===" . PHP_EOL;
echo "Current Date: {$currentDate}" . PHP_EOL;

$todayReturns = DailyReturn::whereDate('return_date', $currentDate)->count();
echo "Returns due today: {$todayReturns}" . PHP_EOL;

$pastReturns = DailyReturn::whereDate('return_date', '<', $currentDate)->count();
echo "Past due returns: {$pastReturns}" . PHP_EOL;

$futureReturns = DailyReturn::whereDate('return_date', '>', $currentDate)->count();
echo "Future returns: {$futureReturns}" . PHP_EOL;

$totalReturns = DailyReturn::count();
echo "Total returns: {$totalReturns}" . PHP_EOL;
