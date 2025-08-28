<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Services\RankingService;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Debug Ranking Calculation ===\n\n";

$rankingService = new RankingService();

// Get a specific user to debug
$testUser = User::where('email', 'test.member@example.com')->first();

if ($testUser) {
    echo "Debugging user: {$testUser->name}\n";
    echo "Current rank: " . ($testUser->rank ?? 'None') . "\n";
    echo "Total investment (field): " . ($testUser->total_investment ?? 0) . "\n";

    // Check investments from database
    $investmentSum = $testUser->investments()->sum('amount');
    echo "Investment sum from investments table: {$investmentSum}\n";

    // Check direct referrals
    $directReferrals = $testUser->referrals()->count();
    echo "Direct referrals: {$directReferrals}\n";

    // Manually calculate what rank should be
    $calculatedRank = $rankingService->calculateUserRank($testUser);
    echo "Calculated rank: {$calculatedRank}\n";

    // Check rank qualification step by step
    echo "\nRank qualification check:\n";
    echo "- Has $5+ investment? " . (($testUser->total_investment ?? 0) >= 5 ? 'YES' : 'NO') . "\n";
    echo "- Has 5+ direct referrals? " . ($directReferrals >= 5 ? 'YES' : 'NO') . "\n";

    if (($testUser->total_investment ?? 0) >= 5 && $directReferrals >= 5) {
        echo "- Should qualify for Counsellor!\n";
    }

    // Force update rank
    echo "\nForcing rank update...\n";
    $updated = $rankingService->updateUserRank($testUser);
    echo "Rank update result: " . ($updated ? 'CHANGED' : 'NO CHANGE') . "\n";
    echo "New rank: " . $testUser->rank . "\n";

} else {
    echo "Test user not found. Let me check all users:\n\n";

    $users = User::all();
    foreach ($users as $user) {
        $directReferrals = $user->referrals()->count();
        $investmentSum = $user->investments()->sum('amount');

        echo "User: {$user->name}\n";
        echo "  - Email: {$user->email}\n";
        echo "  - Current rank: " . ($user->rank ?? 'None') . "\n";
        echo "  - Total investment field: " . ($user->total_investment ?? 0) . "\n";
        echo "  - Investment sum: {$investmentSum}\n";
        echo "  - Direct referrals: {$directReferrals}\n";
        echo "  - Calculated rank: " . $rankingService->calculateUserRank($user) . "\n\n";
    }
}

echo "\n=== Debug completed ===\n";
