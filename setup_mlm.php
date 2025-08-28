<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Investment;

echo "=== Investment Analysis ===" . PHP_EOL;

$investments = Investment::with('user')->get();

foreach ($investments as $investment) {
    echo "Investment ID: {$investment->id}" . PHP_EOL;
    echo "  User ID: {$investment->user_id}" . PHP_EOL;
    echo "  User Name: {$investment->user->name}" . PHP_EOL;
    echo "  Amount: {$investment->amount}" . PHP_EOL;
    echo "  Status: {$investment->status}" . PHP_EOL;
    echo "---" . PHP_EOL;
}

echo PHP_EOL . "=== Setting up MLM Structure for Testing ===" . PHP_EOL;

// Let's create a simple MLM structure for testing
$users = User::all();

if ($users->count() >= 4) {
    // User 1 -> User 2 -> User 3 -> User 4
    $user2 = $users[1]; // Admin User
    $user3 = $users[2]; // Test User
    $user4 = $users[3]; // Abdullah Hel Kafy (duplicate)

    // Set up the MLM chain
    $user2->sponsor_id = 1; // User 2's sponsor is User 1
    $user2->save();

    $user3->sponsor_id = 2; // User 3's sponsor is User 2
    $user3->save();

    $user4->sponsor_id = 3; // User 4's sponsor is User 3
    $user4->save();

    echo "MLM structure created:" . PHP_EOL;
    echo "  User 1 (Abdullah Hel Kafy) -> Root" . PHP_EOL;
    echo "  User 2 (Admin User) -> Sponsored by User 1" . PHP_EOL;
    echo "  User 3 (Test User) -> Sponsored by User 2" . PHP_EOL;
    echo "  User 4 (Abdullah Hel Kafy) -> Sponsored by User 3" . PHP_EOL;

    // Now let's move the investments to User 4 so they have upline
    $investmentUser = User::find(4); // The user with sponsor

    foreach ($investments as $investment) {
        $investment->user_id = 4; // Move to User 4 who has upline
        $investment->save();
    }

    echo PHP_EOL . "Moved all investments to User 4 who has upline structure." . PHP_EOL;

    // Test upline for User 4
    $user4Fresh = User::find(4);
    $upline = $user4Fresh->getUplineUsers();

    echo "User 4 upline:" . PHP_EOL;
    foreach ($upline as $u) {
        echo "  Level {$u['level']}: {$u['user']->name}" . PHP_EOL;
    }

} else {
    echo "Not enough users to create MLM structure!" . PHP_EOL;
}
