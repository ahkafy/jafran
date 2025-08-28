<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Commission;
use App\Models\Investment;

echo "=== Commission Analysis ===" . PHP_EOL;

$totalCommissions = Commission::count();
$pendingCommissions = Commission::where('status', 'pending')->count();
$paidCommissions = Commission::where('status', 'paid')->count();

echo "Total commissions: {$totalCommissions}" . PHP_EOL;
echo "Pending commissions: {$pendingCommissions}" . PHP_EOL;
echo "Paid commissions: {$paidCommissions}" . PHP_EOL;

echo PHP_EOL . "=== Commission Details ===" . PHP_EOL;

$commissions = Commission::with(['user', 'investment'])->get();

foreach ($commissions as $commission) {
    echo "Commission ID: {$commission->id}" . PHP_EOL;
    echo "  User: {$commission->user->name}" . PHP_EOL;
    echo "  Amount: {$commission->amount}" . PHP_EOL;
    echo "  Status: {$commission->status}" . PHP_EOL;
    echo "  Investment Status: {$commission->investment->status}" . PHP_EOL;
    echo "  Investment ID: {$commission->investment_id}" . PHP_EOL;
    echo "---" . PHP_EOL;
}

echo PHP_EOL . "=== Investment Analysis ===" . PHP_EOL;

$activeInvestments = Investment::where('status', 'active')->count();
$totalInvestments = Investment::count();

echo "Total investments: {$totalInvestments}" . PHP_EOL;
echo "Active investments: {$activeInvestments}" . PHP_EOL;

echo PHP_EOL . "=== Attempting to Process Commissions ===" . PHP_EOL;

$mlmService = app(\App\Services\MLMService::class);
$processedCount = $mlmService->processCommissions();

echo "Processed commissions: {$processedCount}" . PHP_EOL;

// Check again after processing
$newPendingCommissions = Commission::where('status', 'pending')->count();
$newPaidCommissions = Commission::where('status', 'paid')->count();

echo "After processing:" . PHP_EOL;
echo "  Pending commissions: {$newPendingCommissions}" . PHP_EOL;
echo "  Paid commissions: {$newPaidCommissions}" . PHP_EOL;

// Check user commission balances
echo PHP_EOL . "=== User Commission Balances ===" . PHP_EOL;

$users = \App\Models\User::whereNotNull('referrer_id')->get(['id', 'name', 'commission_balance']);

foreach ($users as $user) {
    echo "User: {$user->name}, Commission Balance: {$user->commission_balance}" . PHP_EOL;
}
