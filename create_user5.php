<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== Creating User 5 for Level 5 Testing ===" . PHP_EOL;

// Create User 5
$user5 = User::create([
    'name' => 'Level 5 User',
    'email' => 'level5@example.com',
    'password' => bcrypt('password'),
    'sponsor_id' => 4, // Sponsored by User 4
    'wallet_balance' => 100.00,
    'commission_balance' => 0.00,
]);

echo "Created User 5: {$user5->name} (ID: {$user5->id})" . PHP_EOL;
echo "Sponsor: User 4" . PHP_EOL;
echo "Wallet Balance: \${$user5->wallet_balance}" . PHP_EOL;

// Show current user count
$totalUsers = User::count();
echo PHP_EOL . "Total users in system: {$totalUsers}" . PHP_EOL;
