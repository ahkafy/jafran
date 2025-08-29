<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Investment;
use App\Models\InvestmentPackage;
use App\Services\RankingService;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Upgrading Kafy's Investment to Test Rankings ===\n\n";

// Find Kafy user
$kafy = User::where('name', 'like', '%Kafy%')->first();
$package = InvestmentPackage::first();

if (!$kafy || !$package) {
    echo "❌ Kafy user or investment package not found!\n";
    exit;
}

echo "👑 Upgrading: {$kafy->name}\n";
echo "Direct Referrals: {$kafy->referrals()->count()}\n";

// Give Kafy a substantial investment
$investmentAmount = 50.00;

$investment = Investment::create([
    'user_id' => $kafy->id,
    'investment_package_id' => $package->id,
    'amount' => $investmentAmount,
    'daily_return' => $investmentAmount * 0.005,
    'start_date' => now(),
    'end_date' => now()->addDays(30),
    'status' => 'active'
]);

echo "💰 Created investment of $" . number_format($investmentAmount, 2) . "\n";

// Update ranks for entire network
$rankingService = new RankingService();
$updated = $rankingService->updateAllUserRanks();
echo "🏆 Updated ranks for {$updated} users\n\n";

// Refresh user data
$kafy->refresh();

echo "=== KAFY'S NEW STATUS ===\n";
echo "New Rank: " . ($kafy->rank ?? 'Guest') . "\n";
echo "Total Investment: $" . number_format($kafy->investments()->sum('amount'), 2) . "\n";
echo "Direct Referrals: {$kafy->referrals()->count()}\n";

// Check if he qualifies for Counsellor (needs 5+ direct referrals)
if ($kafy->referrals()->count() >= 5) {
    echo "🎉 Qualifies for Counsellor rank!\n";
} else {
    $needed = 5 - $kafy->referrals()->count();
    echo "⚠️ Needs {$needed} more direct referrals for Counsellor rank\n";
}

// Show rank progress
$progress = $kafy->getRankProgress();
if ($progress['next_rank']) {
    echo "\nNext Rank: {$progress['next_rank']}\n";
    echo "Progress:\n";
    foreach ($progress['progress'] as $req => $data) {
        $status = $data['met'] ? '✅' : '❌';
        $percentage = round(($data['current'] / $data['required']) * 100, 1);
        echo "  {$status} " . ucwords(str_replace('_', ' ', $req)) . ": {$data['current']}/{$data['required']} ({$percentage}%)\n";
    }
} else {
    echo "🏆 Maximum rank achieved!\n";
}

// Show updated potential commissions
echo "\n💰 UPDATED COMMISSION POTENTIAL:\n";
$level1Investment = $kafy->referrals->sum(function($user) {
    return $user->investments()->sum('amount');
});

$level2Investment = 0;
foreach ($kafy->referrals as $l1) {
    $level2Investment += $l1->referrals->sum(function($user) {
        return $user->investments()->sum('amount');
    });
}

echo "Level 1: $" . number_format($level1Investment, 2) . " × 10% = $" . number_format($level1Investment * 0.10, 2) . "\n";
echo "Level 2: $" . number_format($level2Investment, 2) . " × 4% = $" . number_format($level2Investment * 0.04, 2) . "\n";

echo "\n✅ Kafy's status upgrade completed!\n";
