<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Investment;
use App\Models\InvestmentPackage;
use App\Services\RankingService;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Creating Real Investments for Ranking Test ===\n\n";

// Get investment packages
$packages = InvestmentPackage::all();
if ($packages->isEmpty()) {
    echo "❌ No investment packages found. Creating sample package...\n";
    $package = InvestmentPackage::create([
        'name' => 'Basic Package',
        'min_amount' => 5,
        'max_amount' => 1000,
        'daily_return_rate' => 0.5,
        'return_days' => 30,
        'status' => 'active'
    ]);
} else {
    $package = $packages->first();
}

echo "✅ Using package: {$package->name}\n\n";

// Create investments for some users
$testUser = User::where('email', 'test.member@example.com')->first();

if ($testUser) {
    echo "Creating investment for: {$testUser->name}\n";

    // Create investment
    $investment = Investment::create([
        'user_id' => $testUser->id,
        'investment_package_id' => $package->id,
        'amount' => 25.00,
        'daily_return' => 0.125,
        'start_date' => now(),
        'end_date' => now()->addDays(30),
        'status' => 'active'
    ]);

    echo "✅ Created investment of $25.00\n";

    // Create investments for some referrals too
    $referrals = $testUser->referrals()->take(4)->get();
    foreach ($referrals as $i => $referral) {
        $amount = [10, 15, 8, 12][$i];

        $investment = Investment::create([
            'user_id' => $referral->id,
            'investment_package_id' => $package->id,
            'amount' => $amount,
            'daily_return' => $amount * 0.005,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'status' => 'active'
        ]);

        echo "✅ Created investment of $" . $amount . ".00 for {$referral->name}\n";
    }
}

// Let's also give one of the existing users who has $15 investment some more referrals
$existingUser = User::where('total_investment', '>', 10)->first();
if ($existingUser && $existingUser->referrals()->count() < 5) {
    echo "\nCreating additional referrals for {$existingUser->name}...\n";

    $currentReferrals = $existingUser->referrals()->count();
    $needed = 5 - $currentReferrals;

    for ($i = 1; $i <= $needed; $i++) {
        $referral = User::create([
            'name' => "Additional Referral $i",
            'email' => "additional.ref{$i}@example.com",
            'password' => bcrypt('password'),
            'sponsor_id' => $existingUser->id,
            'wallet_balance' => 50,
            'status' => 'active'
        ]);

        // Give them a small investment too
        $investment = Investment::create([
            'user_id' => $referral->id,
            'investment_package_id' => $package->id,
            'amount' => 7.00,
            'daily_return' => 0.035,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'status' => 'active'
        ]);

        echo "✅ Created additional referral {$referral->name} with $7 investment\n";
    }
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "Now updating all user ranks...\n";

$rankingService = new RankingService();
$updated = $rankingService->updateAllUserRanks();
echo "✅ Updated ranks for {$updated} users\n\n";

// Show results
echo "Updated Rankings:\n";
echo str_repeat("-", 80) . "\n";
echo sprintf("%-25s %-15s %-15s %-15s\n",
    "Name", "Rank", "Total Investment", "Direct Referrals");
echo str_repeat("-", 80) . "\n";

$users = User::with('referrals', 'investments')->get();
foreach ($users as $user) {
    $totalInvestment = $user->investments()->sum('amount');
    $directReferrals = $user->referrals()->count();

    echo sprintf("%-25s %-15s $%-14.2f %-15d\n",
        substr($user->name, 0, 24),
        $user->rank ?? 'Guest',
        $totalInvestment,
        $directReferrals
    );
}

echo "\n" . str_repeat("=", 80) . "\n";

// Check for specific rank achievements
$members = User::where('rank', 'Member')->count();
$counsellors = User::where('rank', 'Counsellor')->count();
$leaders = User::where('rank', 'Leader')->count();

echo "🏆 Rank Distribution:\n";
echo "- Members: {$members}\n";
echo "- Counsellors: {$counsellors}\n";
echo "- Leaders: {$leaders}\n";

if ($counsellors > 0) {
    echo "\n🎉 Congratulations! We have Counsellor rank achieved!\n";
    $counsellorUsers = User::where('rank', 'Counsellor')->get();
    foreach ($counsellorUsers as $counsellor) {
        echo "- {$counsellor->name} achieved Counsellor rank!\n";
    }
}

echo "\n✅ Ranking test with real investments completed!\n";
