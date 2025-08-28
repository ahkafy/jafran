<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Investment;
use App\Models\InvestmentPackage;
use App\Services\RankingService;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Creating Test Data for Ranking System ===\n\n";

// First, let's create a sample structure
$rankingService = new RankingService();

// Check if we already have a test structure
$testUser = User::where('email', 'test.member@example.com')->first();

if (!$testUser) {
    echo "Creating test users and investments...\n";

    // Create a user who should be Member rank
    $memberUser = User::create([
        'name' => 'Test Member',
        'email' => 'test.member@example.com',
        'password' => bcrypt('password'),
        'wallet_balance' => 1000,
        'status' => 'active'
    ]);

    // Create some referrals for the member
    $referrals = [];
    for ($i = 1; $i <= 6; $i++) {
        $referral = User::create([
            'name' => "Referral User $i",
            'email' => "referral{$i}@example.com",
            'password' => bcrypt('password'),
            'sponsor_id' => $memberUser->id,
            'wallet_balance' => 100,
            'status' => 'active'
        ]);
        $referrals[] = $referral;

        // Give some of them investments to make them Members
        if ($i <= 3) {
            // Simulate investment by updating total_investment directly
            $referral->update(['total_investment' => 10]);
        }
    }

    // Give the main user a good investment
    $memberUser->update(['total_investment' => 50]);

    echo "✅ Created test user structure\n";
} else {
    echo "Test structure already exists\n";
}

// Update all ranks
echo "\nUpdating all user ranks...\n";
$updated = $rankingService->updateAllUserRanks();
echo "✅ Updated ranks for {$updated} users\n\n";

// Show the current rankings
echo "Current User Rankings After Test:\n";
echo str_repeat("-", 90) . "\n";
echo sprintf("%-25s %-15s %-15s %-15s %-10s\n",
    "Name", "Rank", "Total Investment", "Direct Referrals", "Team Size");
echo str_repeat("-", 90) . "\n";

$users = User::with('referrals')->get();
foreach ($users as $user) {
    $directReferrals = $user->referrals()->count();
    $teamSize = $user->getTotalTeamSize();

    echo sprintf("%-25s %-15s $%-14.2f %-15d %-10d\n",
        substr($user->name, 0, 24),
        $user->rank ?? 'Guest',
        $user->total_investment ?? 0,
        $directReferrals,
        $teamSize
    );
}

echo "\n" . str_repeat("=", 90) . "\n";

// Check if anyone qualifies for Counsellor
$counsellorCandidates = User::where('total_investment', '>=', 5)
    ->whereHas('referrals', function($q) {
        // Count how many have direct referrals >= 5
    })
    ->with('referrals')
    ->get()
    ->filter(function($user) {
        return $user->referrals()->count() >= 5;
    });

if ($counsellorCandidates->count() > 0) {
    echo "\n🎉 Users eligible for Counsellor rank:\n";
    foreach ($counsellorCandidates as $candidate) {
        echo "- {$candidate->name} (Investment: ${$candidate->total_investment}, Direct Referrals: {$candidate->referrals()->count()})\n";
    }
} else {
    echo "\n📊 No users currently qualify for Counsellor rank\n";
    echo "Requirements: $5+ investment + 5+ direct referrals\n";
}

echo "\n✅ Test data creation and ranking completed!\n";
