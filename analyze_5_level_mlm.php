<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Services\RankingService;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== 5-Level MLM Structure Analysis ===\n\n";

// Find Kafy user
$kafy = User::where('name', 'like', '%Kafy%')->first();

if (!$kafy) {
    echo "❌ Kafy user not found!\n";
    exit;
}

echo "👑 Root User: {$kafy->name} (ID: {$kafy->id})\n";
echo "Current Rank: " . ($kafy->rank ?? 'Guest') . "\n";
echo "Referral Code: {$kafy->referral_code}\n\n";

// Analyze each level
function analyzeLevel($users, $levelName) {
    echo "📊 {$levelName}:\n";
    echo str_repeat("-", 50) . "\n";

    if ($users->isEmpty()) {
        echo "  No users at this level\n\n";
        return;
    }

    $rankCounts = $users->groupBy('rank')->map->count();
    $totalInvestments = $users->sum(function($user) {
        return $user->investments()->sum('amount');
    });

    echo "  Total Users: {$users->count()}\n";
    echo "  Total Investments: $" . number_format($totalInvestments, 2) . "\n";

    echo "  Rank Distribution:\n";
    foreach (['Guest', 'Member', 'Counsellor', 'Leader', 'Trainer', 'Senior Trainer'] as $rank) {
        $count = $rankCounts->get($rank, 0);
        if ($count > 0) {
            $percentage = round(($count / $users->count()) * 100, 1);
            echo "    - {$rank}: {$count} users ({$percentage}%)\n";
        }
    }

    // Show some sample users
    echo "  Sample Users:\n";
    foreach ($users->take(3) as $user) {
        $investment = $user->investments()->sum('amount');
        $directReferrals = $user->referrals()->count();
        echo "    - {$user->name} | Rank: " . ($user->rank ?? 'Guest') .
             " | Investment: $" . number_format($investment, 2) .
             " | Direct: {$directReferrals}\n";
    }

    echo "\n";
}

// Get all levels
$level1Users = $kafy->referrals;
$level2Users = collect();
$level3Users = collect();
$level4Users = collect();
$level5Users = collect();

foreach ($level1Users as $l1) {
    $level2Users = $level2Users->merge($l1->referrals);
}

foreach ($level2Users as $l2) {
    $level3Users = $level3Users->merge($l2->referrals);
}

foreach ($level3Users as $l3) {
    $level4Users = $level4Users->merge($l3->referrals);
}

foreach ($level4Users as $l4) {
    $level5Users = $level5Users->merge($l4->referrals);
}

// Analyze each level
analyzeLevel($level1Users, "Level 1 (Direct Referrals)");
analyzeLevel($level2Users, "Level 2");
analyzeLevel($level3Users, "Level 3");
analyzeLevel($level4Users, "Level 4");
analyzeLevel($level5Users, "Level 5");

// Overall network analysis
$allNetworkUsers = $level1Users->merge($level2Users)->merge($level3Users)->merge($level4Users)->merge($level5Users);

echo str_repeat("=", 60) . "\n";
echo "🌐 OVERALL NETWORK ANALYSIS\n";
echo str_repeat("=", 60) . "\n";

$totalNetworkSize = $allNetworkUsers->count();
$totalNetworkInvestments = $allNetworkUsers->sum(function($user) {
    return $user->investments()->sum('amount');
});

echo "Total Network Size: {$totalNetworkSize} users\n";
echo "Total Network Investments: $" . number_format($totalNetworkInvestments, 2) . "\n";

// Rank distribution across entire network
$networkRankCounts = $allNetworkUsers->groupBy('rank')->map->count();
echo "\nNetwork Rank Distribution:\n";
foreach (['Guest', 'Member', 'Counsellor', 'Leader', 'Trainer', 'Senior Trainer'] as $rank) {
    $count = $networkRankCounts->get($rank, 0);
    $percentage = $totalNetworkSize > 0 ? round(($count / $totalNetworkSize) * 100, 1) : 0;
    echo "  {$rank}: {$count} users ({$percentage}%)\n";
}

// Potential commissions for Kafy
echo "\n💰 POTENTIAL COMMISSIONS FOR KAFY:\n";
$commissionRates = [1 => 10, 2 => 4, 3 => 3, 4 => 2, 5 => 2];
$totalPotentialCommissions = 0;

foreach ([1 => $level1Users, 2 => $level2Users, 3 => $level3Users, 4 => $level4Users, 5 => $level5Users] as $level => $users) {
    $levelInvestments = $users->sum(function($user) {
        return $user->investments()->sum('amount');
    });
    $levelCommissions = $levelInvestments * ($commissionRates[$level] / 100);
    $totalPotentialCommissions += $levelCommissions;

    echo "  Level {$level}: $" . number_format($levelInvestments, 2) .
         " investments × {$commissionRates[$level]}% = $" . number_format($levelCommissions, 2) . " commission\n";
}

echo "  TOTAL POTENTIAL: $" . number_format($totalPotentialCommissions, 2) . "\n";

// Check Kafy's rank potential
echo "\n🏆 KAFY'S RANK ANALYSIS:\n";
$kafyDirectReferrals = $kafy->referrals()->count();
$kafyTotalInvestment = $kafy->investments()->sum('amount');

echo "  Current Rank: " . ($kafy->rank ?? 'Guest') . "\n";
echo "  Direct Referrals: {$kafyDirectReferrals}\n";
echo "  Total Investment: $" . number_format($kafyTotalInvestment, 2) . "\n";

$rankingService = new RankingService();
$progress = $kafy->getRankProgress();

if ($progress['next_rank']) {
    echo "  Next Rank: {$progress['next_rank']}\n";
    echo "  Requirements:\n";
    foreach ($progress['progress'] as $req => $data) {
        $status = $data['met'] ? '✅' : '❌';
        echo "    {$status} " . ucwords(str_replace('_', ' ', $req)) . ": {$data['current']}/{$data['required']}\n";
    }
} else {
    echo "  🏆 Maximum rank achieved!\n";
}

echo "\n✅ Analysis completed!\n";
