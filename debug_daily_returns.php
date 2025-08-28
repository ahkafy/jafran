<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\DailyReturn;
use App\Models\Investment;

echo "=== Daily Returns Analysis ===" . PHP_EOL;

$totalDailyReturns = DailyReturn::count();
echo "Total daily returns in database: {$totalDailyReturns}" . PHP_EOL;

$dailyReturns = DailyReturn::with(['user', 'investment', 'investment.investmentPackage'])->get();

echo PHP_EOL . "Daily Returns Details:" . PHP_EOL;
foreach ($dailyReturns as $return) {
    echo "Daily Return ID: {$return->id}" . PHP_EOL;
    echo "  User ID: {$return->user_id}" . PHP_EOL;
    echo "  User Name: {$return->user->name}" . PHP_EOL;
    echo "  Investment ID: {$return->investment_id}" . PHP_EOL;
    echo "  Amount: {$return->amount}" . PHP_EOL;
    echo "  Return Date: {$return->return_date}" . PHP_EOL;
    echo "  Status: {$return->status}" . PHP_EOL;
    echo "  Day Number: {$return->day_number}" . PHP_EOL;
    echo "  Investment Amount: {$return->investment->amount}" . PHP_EOL;
    echo "  Package Name: {$return->investment->investmentPackage->name}" . PHP_EOL;
    echo "---" . PHP_EOL;
}

echo PHP_EOL . "=== Users and their Daily Returns ===" . PHP_EOL;

$users = User::with('dailyReturns')->get();

foreach ($users as $user) {
    $dailyReturnsCount = $user->dailyReturns->count();
    echo "User ID: {$user->id}, Name: {$user->name}" . PHP_EOL;
    echo "  Daily Returns Count: {$dailyReturnsCount}" . PHP_EOL;

    if ($dailyReturnsCount > 0) {
        $totalAmount = $user->dailyReturns->sum('amount');
        echo "  Total Daily Returns Amount: ${$totalAmount}" . PHP_EOL;
        echo "  Latest Daily Return: {$user->dailyReturns->max('return_date')}" . PHP_EOL;
    }
    echo "---" . PHP_EOL;
}

echo PHP_EOL . "=== Investment to Daily Return Mapping ===" . PHP_EOL;

$investments = Investment::with(['dailyReturns', 'user'])->get();

foreach ($investments as $investment) {
    $dailyReturnsCount = $investment->dailyReturns->count();
    echo "Investment ID: {$investment->id}" . PHP_EOL;
    echo "  User: {$investment->user->name}" . PHP_EOL;
    echo "  Amount: {$investment->amount}" . PHP_EOL;
    echo "  Status: {$investment->status}" . PHP_EOL;
    echo "  Daily Returns Count: {$dailyReturnsCount}" . PHP_EOL;

    if ($dailyReturnsCount > 0) {
        echo "  Daily Returns:" . PHP_EOL;
        foreach ($investment->dailyReturns as $return) {
            echo "    Date: {$return->return_date}, Amount: {$return->amount}, Status: {$return->status}" . PHP_EOL;
        }
    }
    echo "---" . PHP_EOL;
}
