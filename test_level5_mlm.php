<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Investment;
use App\Models\Commission;
use App\Services\MLMService;

echo "=== Testing Level 5 MLM Commission ===" . PHP_EOL;

// Create a 5-level MLM structure
$users = User::all();

if ($users->count() >= 5) {
    // Let's create a deep 5-level structure
    // User 1 -> User 2 -> User 3 -> User 4 -> User 5

    // First, let's create User 5 if it doesn't exist
    $user5 = User::find(5);
    if (!$user5) {
        $user5 = User::create([
            'name' => 'Level 5 User',
            'email' => 'level5@example.com',
            'password' => bcrypt('password'),
            'sponsor_id' => 4, // Sponsored by User 4
            'wallet_balance' => 100.00,
            'commission_balance' => 0.00,
        ]);
        echo "Created User 5: {$user5->name}" . PHP_EOL;
    } else {
        // Update sponsor if needed
        $user5->sponsor_id = 4;
        $user5->save();
        echo "Updated User 5 sponsor to User 4" . PHP_EOL;
    }

    // Show the MLM structure
    echo PHP_EOL . "MLM Structure:" . PHP_EOL;
    echo "User 1 (Root)" . PHP_EOL;
    echo "  └── User 2 (Level 1)" . PHP_EOL;
    echo "      └── User 3 (Level 2)" . PHP_EOL;
    echo "          └── User 4 (Level 3)" . PHP_EOL;
    echo "              └── User 5 (Level 4)" . PHP_EOL;

    // Test upline for User 5
    $upline = $user5->getUplineUsers();
    echo PHP_EOL . "User 5 upline:" . PHP_EOL;
    foreach ($upline as $u) {
        echo "  Level {$u['level']}: {$u['user']->name} (ID: {$u['user']->id})" . PHP_EOL;
    }

    // Create an investment for User 5
    $mlmService = app(MLMService::class);

    try {
        $investment = $mlmService->createInvestment($user5, 1, 10.00); // $10 investment
        echo PHP_EOL . "Created investment for User 5: \${$investment->amount}" . PHP_EOL;

        // Check commissions created
        $commissions = Commission::where('investment_id', $investment->id)->with('user')->get();

        echo PHP_EOL . "Commissions created:" . PHP_EOL;
        foreach ($commissions as $commission) {
            echo "  Level {$commission->level}: {$commission->user->name} - \${$commission->amount} ({$commission->percentage}%)" . PHP_EOL;
        }

        // Check if Level 5 commission was created
        $level5Commission = $commissions->where('level', 5)->first();
        if ($level5Commission) {
            echo PHP_EOL . "✅ Level 5 commission successfully created!" . PHP_EOL;
            echo "   User: {$level5Commission->user->name}" . PHP_EOL;
            echo "   Amount: \${$level5Commission->amount}" . PHP_EOL;
            echo "   Percentage: {$level5Commission->percentage}%" . PHP_EOL;
        } else {
            echo PHP_EOL . "❌ Level 5 commission was NOT created!" . PHP_EOL;
        }

    } catch (Exception $e) {
        echo "Error creating investment: " . $e->getMessage() . PHP_EOL;
    }

} else {
    echo "Not enough users to test 5-level structure!" . PHP_EOL;
}
