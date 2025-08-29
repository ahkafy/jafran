<?php

require_once 'vendor/autoload.php';

use App\Models\User;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Finding Kafy User ===\n\n";

$kafyUsers = User::where('name', 'like', '%Kafy%')->get();

if ($kafyUsers->count() > 0) {
    foreach ($kafyUsers as $user) {
        echo "Found user: {$user->name} (ID: {$user->id}, Email: {$user->email}, Code: {$user->referral_code})\n";
    }
} else {
    echo "No user found with 'Kafy' in the name. Let me show all users:\n\n";
    $allUsers = User::all(['id', 'name', 'email', 'referral_code']);
    foreach ($allUsers as $user) {
        echo "ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Code: {$user->referral_code}\n";
    }
}

echo "\n✅ User search completed!\n";
