<?php

// Debug team stats
require_once __DIR__ . '/bootstrap/app.php';

use App\Models\User;
use App\Services\MLMService;
use Illuminate\Foundation\Application;

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

// Find a user to test with
$user = User::first(); // Get first user

if ($user) {
    echo "Testing with user: {$user->name}\n";
    echo "User ID: {$user->id}\n";
    echo "User rank: " . ($user->rank ?? 'null') . "\n\n";

    $mlmService = new MLMService();

    try {
        $stats = $mlmService->getUserMLMStats($user);

        echo "Stats retrieved successfully:\n";
        echo "Direct referrals: " . ($stats['direct_referrals'] ?? 'null') . "\n";
        echo "Total team: " . ($stats['total_team'] ?? 'null') . "\n";
        echo "Total investment: " . ($stats['total_investment'] ?? 'null') . "\n";
        echo "Total commissions: " . ($stats['total_commissions'] ?? 'null') . "\n";
        echo "Team investments:\n";

        for ($level = 1; $level <= 5; $level++) {
            $amount = $stats['team_investments'][$level] ?? 0;
            echo "  Level {$level}: $" . number_format($amount, 2) . "\n";
        }

    } catch (Exception $e) {
        echo "Error getting stats: " . $e->getMessage() . "\n";
        echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    }
} else {
    echo "No users found in database.\n";
}

echo "\nTesting completed.\n";

?>
