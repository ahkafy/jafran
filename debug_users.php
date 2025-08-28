<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Services\MLMService;

echo "=== User Structure Analysis ===" . PHP_EOL;

$users = User::all();

foreach ($users as $user) {
    echo "User ID: {$user->id}, Name: {$user->name}" . PHP_EOL;
    echo "  sponsor_id: " . ($user->sponsor_id ?? 'NULL') . PHP_EOL;
    echo "  referrer_id: " . ($user->referrer_id ?? 'NULL') . PHP_EOL;

    // Test the sponsor relationship
    try {
        $sponsor = $user->sponsor;
        echo "  Sponsor: " . ($sponsor ? $sponsor->name : 'NULL') . PHP_EOL;
    } catch (Exception $e) {
        echo "  Sponsor relationship error: " . $e->getMessage() . PHP_EOL;
    }

    // Test getting upline users
    try {
        $upline = $user->getUplineUsers();
        echo "  Upline count: " . count($upline) . PHP_EOL;
        foreach ($upline as $u) {
            echo "    Level {$u['level']}: {$u['user']->name}" . PHP_EOL;
        }
    } catch (Exception $e) {
        echo "  Upline error: " . $e->getMessage() . PHP_EOL;
    }

    echo "---" . PHP_EOL;
}

echo PHP_EOL . "=== Testing Commission Generation for User ID 1 ===" . PHP_EOL;

$testUser = User::find(1);
if ($testUser) {
    echo "Testing commission generation for: {$testUser->name}" . PHP_EOL;

    $mlmService = app(MLMService::class);

    // Get first investment for testing
    $investment = $testUser->investments()->first();

    if ($investment) {
        echo "Testing with Investment ID: {$investment->id}, Amount: {$investment->amount}" . PHP_EOL;

        // Get upline before commission calculation
        $upline = $testUser->getUplineUsers();
        echo "Upline users: " . count($upline) . PHP_EOL;

        if (count($upline) > 0) {
            echo "Will create commissions for:" . PHP_EOL;
            foreach ($upline as $u) {
                echo "  Level {$u['level']}: {$u['user']->name}" . PHP_EOL;
            }

            // Try to create commissions
            try {
                $mlmService->calculateCommissions($testUser, $investment, $investment->amount);
                echo "Commission calculation completed!" . PHP_EOL;

                // Check if commissions were created
                $commissionCount = \App\Models\Commission::count();
                echo "Total commissions after creation: {$commissionCount}" . PHP_EOL;

            } catch (Exception $e) {
                echo "Commission calculation error: " . $e->getMessage() . PHP_EOL;
            }
        } else {
            echo "No upline users found - no commissions to create!" . PHP_EOL;
        }
    } else {
        echo "No investments found for this user!" . PHP_EOL;
    }
} else {
    echo "User with ID 1 not found!" . PHP_EOL;
}
