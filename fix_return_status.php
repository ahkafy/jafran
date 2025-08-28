<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DailyReturn;

echo "=== Checking and Fixing Daily Return Status ===" . PHP_EOL;

// Check returns that were processed but have wrong status
$processedReturns = DailyReturn::where('status', 'processed')->get();

echo "Found {$processedReturns->count()} returns with 'processed' status." . PHP_EOL;

foreach ($processedReturns as $return) {
    echo "Fixing Return ID {$return->id} (Date: {$return->return_date->format('Y-m-d')})" . PHP_EOL;
    $return->update(['status' => 'paid']);
}

echo "All processed returns updated to 'paid' status." . PHP_EOL;

// Now process pending returns for past dates
$currentDate = now();
$pastPendingReturns = DailyReturn::where('status', 'pending')
    ->where('return_date', '<=', $currentDate)
    ->get();

echo PHP_EOL . "Found {$pastPendingReturns->count()} pending returns for past/current dates." . PHP_EOL;

foreach ($pastPendingReturns as $return) {
    if ($return->investment && $return->investment->status === 'active') {
        echo "Processing Return ID {$return->id} (Date: {$return->return_date->format('Y-m-d')})" . PHP_EOL;
        $return->markAsProcessed();
    }
}

echo "All past pending returns processed." . PHP_EOL;

// Final status check
echo PHP_EOL . "=== Final Status Check ===" . PHP_EOL;

$totalReturns = DailyReturn::count();
$paidReturns = DailyReturn::where('status', 'paid')->count();
$pendingReturns = DailyReturn::where('status', 'pending')->count();

echo "Total returns: {$totalReturns}" . PHP_EOL;
echo "Paid returns: {$paidReturns}" . PHP_EOL;
echo "Pending returns: {$pendingReturns}" . PHP_EOL;

// Check amounts by date
$todayAmount = DailyReturn::whereDate('return_date', $currentDate->format('Y-m-d'))
    ->where('status', 'paid')
    ->sum('amount');

$weekAmount = DailyReturn::where('return_date', '>=', $currentDate->startOfWeek())
    ->where('status', 'paid')
    ->sum('amount');

$monthAmount = DailyReturn::where('return_date', '>=', $currentDate->startOfMonth())
    ->where('status', 'paid')
    ->sum('amount');

echo PHP_EOL . "Paid amounts:" . PHP_EOL;
echo "Today: \${$todayAmount}" . PHP_EOL;
echo "This week: \${$weekAmount}" . PHP_EOL;
echo "This month: \${$monthAmount}" . PHP_EOL;
