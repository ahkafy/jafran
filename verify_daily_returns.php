<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DailyReturn;
use App\Models\User;

echo "=== Daily Returns Status Check ===" . PHP_EOL;

$currentDate = now();
echo "Current Date: {$currentDate->format('Y-m-d')}" . PHP_EOL;

// Check by status
$paidReturns = DailyReturn::where('status', 'paid')->count();
$pendingReturns = DailyReturn::where('status', 'pending')->count();

echo "Paid returns: {$paidReturns}" . PHP_EOL;
echo "Pending returns: {$pendingReturns}" . PHP_EOL;

// Check by date range
$todayReturns = DailyReturn::whereDate('return_date', $currentDate)->count();
$weekReturns = DailyReturn::where('return_date', '>=', $currentDate->copy()->startOfWeek())->count();
$monthReturns = DailyReturn::where('return_date', '>=', $currentDate->copy()->startOfMonth())->count();

echo PHP_EOL . "Returns by date range:" . PHP_EOL;
echo "Today: {$todayReturns}" . PHP_EOL;
echo "This week: {$weekReturns}" . PHP_EOL;
echo "This month: {$monthReturns}" . PHP_EOL;

// Check totals by status and amount
$todayPaidAmount = DailyReturn::whereDate('return_date', $currentDate)->where('status', 'paid')->sum('amount');
$weekPaidAmount = DailyReturn::where('return_date', '>=', $currentDate->copy()->startOfWeek())->where('status', 'paid')->sum('amount');
$monthPaidAmount = DailyReturn::where('return_date', '>=', $currentDate->copy()->startOfMonth())->where('status', 'paid')->sum('amount');
$totalPaidAmount = DailyReturn::where('status', 'paid')->sum('amount');

echo PHP_EOL . "Paid amounts:" . PHP_EOL;
echo "Today: \${$todayPaidAmount}" . PHP_EOL;
echo "This week: \${$weekPaidAmount}" . PHP_EOL;
echo "This month: \${$monthPaidAmount}" . PHP_EOL;
echo "Total: \${$totalPaidAmount}" . PHP_EOL;

// Check per user
echo PHP_EOL . "=== User-wise Daily Returns ===" . PHP_EOL;

$users = User::with('dailyReturns')->get();

foreach ($users as $user) {
    $userReturns = $user->dailyReturns->count();
    if ($userReturns > 0) {
        $userPaidReturns = $user->dailyReturns->where('status', 'paid')->count();
        $userPaidAmount = $user->dailyReturns->where('status', 'paid')->sum('amount');
        $userTodayAmount = $user->dailyReturns->where('return_date', $currentDate->format('Y-m-d'))->where('status', 'paid')->sum('amount');

        echo "User: {$user->name}" . PHP_EOL;
        echo "  Total returns: {$userReturns}" . PHP_EOL;
        echo "  Paid returns: {$userPaidReturns}" . PHP_EOL;
        echo "  Total paid amount: \${$userPaidAmount}" . PHP_EOL;
        echo "  Today's amount: \${$userTodayAmount}" . PHP_EOL;
        echo "---" . PHP_EOL;
    }
}
