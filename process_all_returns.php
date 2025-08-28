<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DailyReturn;

echo "=== Processing Past Due Returns ===" . PHP_EOL;

// Process all pending returns for past and current dates
$currentDate = now();
$pastPendingReturns = DailyReturn::where('status', 'pending')
    ->where('return_date', '<=', $currentDate)
    ->with(['investment', 'user'])
    ->get();

echo "Found {$pastPendingReturns->count()} pending returns for past/current dates." . PHP_EOL;

$processedCount = 0;

foreach ($pastPendingReturns as $return) {
    if ($return->investment && $return->investment->status === 'active') {
        echo "Processing Return ID {$return->id} (Date: {$return->return_date->format('Y-m-d')}, User: {$return->user->name})" . PHP_EOL;
        $return->markAsProcessed();
        $processedCount++;
    }
}

echo "Processed {$processedCount} returns." . PHP_EOL;

// Final status check
echo PHP_EOL . "=== Final Status Check ===" . PHP_EOL;

$totalReturns = DailyReturn::count();
$processedReturns = DailyReturn::where('status', 'processed')->count();
$pendingReturns = DailyReturn::where('status', 'pending')->count();

echo "Total returns: {$totalReturns}" . PHP_EOL;
echo "Processed returns: {$processedReturns}" . PHP_EOL;
echo "Pending returns: {$pendingReturns}" . PHP_EOL;

// Check amounts by date
$todayAmount = DailyReturn::whereDate('return_date', $currentDate->format('Y-m-d'))
    ->where('status', 'processed')
    ->sum('amount');

$weekAmount = DailyReturn::where('return_date', '>=', $currentDate->copy()->startOfWeek())
    ->where('status', 'processed')
    ->sum('amount');

$monthAmount = DailyReturn::where('return_date', '>=', $currentDate->copy()->startOfMonth())
    ->where('status', 'processed')
    ->sum('amount');

$totalAmount = DailyReturn::where('status', 'processed')->sum('amount');

echo PHP_EOL . "Processed amounts:" . PHP_EOL;
echo "Today: \${$todayAmount}" . PHP_EOL;
echo "This week: \${$weekAmount}" . PHP_EOL;
echo "This month: \${$monthAmount}" . PHP_EOL;
echo "Total: \${$totalAmount}" . PHP_EOL;
