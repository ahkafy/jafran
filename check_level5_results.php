<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Commission;

echo "=== Final Commission Balance Check ===" . PHP_EOL;

$users = User::all(['id', 'name', 'commission_balance']);

foreach ($users as $user) {
    $commissions = Commission::where('user_id', $user->id)->get();
    $totalCommissions = $commissions->sum('amount');
    $level5Commissions = $commissions->where('level', 5)->sum('amount');

    echo "User {$user->id}: {$user->name}" . PHP_EOL;
    echo "  Commission Balance: \${$user->commission_balance}" . PHP_EOL;
    echo "  Total Commissions Earned: \${$totalCommissions}" . PHP_EOL;

    if ($level5Commissions > 0) {
        echo "  Level 5 Commissions: \${$level5Commissions} ✅" . PHP_EOL;
    }

    echo "---" . PHP_EOL;
}

echo PHP_EOL . "=== Level 5 Commission Summary ===" . PHP_EOL;

$level5Commissions = Commission::where('level', 5)->with('user')->get();

if ($level5Commissions->count() > 0) {
    echo "Total Level 5 commissions: {$level5Commissions->count()}" . PHP_EOL;
    echo "Total Level 5 amount: \${$level5Commissions->sum('amount')}" . PHP_EOL;

    foreach ($level5Commissions as $commission) {
        echo "✅ Level 5 Commission: {$commission->user->name} earned \${$commission->amount} (2%)" . PHP_EOL;
    }
} else {
    echo "No Level 5 commissions found." . PHP_EOL;
}
