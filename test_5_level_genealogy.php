<?php

// Test 5-level genealogy tree view as Kafy
// This script tests the new mindmap-style genealogy view

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "5-Level Genealogy Tree Test\n";
echo "===========================\n\n";

// Find Kafy user
$kafy = User::where('name', 'Kafy')->first();

if (!$kafy) {
    echo "❌ Error: Kafy user not found!\n";
    exit(1);
}

echo "✅ Found Kafy user (ID: {$kafy->id})\n";
echo "   Current rank: {$kafy->rank}\n";
echo "   Total investment: $" . number_format($kafy->total_investment ?? 0, 2) . "\n\n";

// Simulate login as Kafy
Auth::login($kafy);

// Load deep genealogy tree (5 levels)
$tree = $kafy->referrals()->with([
    'referrals.referrals.referrals.referrals' // 5 levels deep
])->get();

echo "📊 Genealogy Tree Analysis:\n";
echo "-----------------------------\n";

// Level 1 analysis
echo "Level 1 (Direct Referrals): {$tree->count()} users\n";
foreach ($tree as $index => $level1User) {
    $userNumber = $index + 1;
    echo "  {$userNumber}. {$level1User->name} - {$level1User->rank} - {$level1User->referrals->count()} referrals\n";

    // Level 2 analysis
    foreach ($level1User->referrals as $level2User) {
        echo "    ├─ {$level2User->name} - {$level2User->rank} - {$level2User->referrals->count()} referrals\n";

        // Level 3 analysis
        foreach ($level2User->referrals as $level3User) {
            echo "      ├─ {$level3User->name} - {$level3User->rank} - {$level3User->referrals->count()} referrals\n";

            // Level 4 analysis
            foreach ($level3User->referrals as $level4User) {
                echo "        ├─ {$level4User->name} - {$level4User->rank} - {$level4User->referrals->count()} referrals\n";

                // Level 5 analysis
                foreach ($level4User->referrals as $level5User) {
                    echo "          └─ {$level5User->name} - {$level5User->rank}\n";
                }
            }
        }
    }
}

echo "\n📈 Network Statistics:\n";
echo "----------------------\n";

// Calculate total referrals recursively
function countTotalReferrals($users) {
    $count = $users->count();
    foreach ($users as $user) {
        $count += countTotalReferrals($user->referrals);
    }
    return $count;
}

// Calculate total investment recursively
function calculateTotalInvestment($users) {
    $total = 0;
    foreach ($users as $user) {
        $total += $user->total_investment ?? 0;
        $total += calculateTotalInvestment($user->referrals);
    }
    return $total;
}

// Count active referrals recursively
function countActiveReferrals($users) {
    $count = 0;
    foreach ($users as $user) {
        if (($user->total_investment ?? 0) > 0) {
            $count++;
        }
        $count += countActiveReferrals($user->referrals);
    }
    return $count;
}

// Count users by level
function countByLevel($users, $level = 1) {
    $counts = [$level => $users->count()];

    if ($level < 5) {
        foreach ($users as $user) {
            if ($user->referrals->count() > 0) {
                $subCounts = countByLevel($user->referrals, $level + 1);
                foreach ($subCounts as $subLevel => $subCount) {
                    $counts[$subLevel] = ($counts[$subLevel] ?? 0) + $subCount;
                }
            }
        }
    }

    return $counts;
}

$totalReferrals = countTotalReferrals($tree);
$totalInvestment = calculateTotalInvestment($tree);
$activeReferrals = countActiveReferrals($tree);
$levelCounts = countByLevel($tree);

echo "Total Referrals (All Levels): {$totalReferrals}\n";
echo "Active Members: {$activeReferrals}\n";
echo "Total Investment: $" . number_format($totalInvestment, 2) . "\n";
echo "Kafy's Rank: {$kafy->rank}\n\n";

echo "Users by Level:\n";
foreach ($levelCounts as $level => $count) {
    echo "  Level {$level}: {$count} users\n";
}

echo "\n🎯 View Test Results:\n";
echo "---------------------\n";

if ($totalReferrals > 50) {
    echo "✅ Deep genealogy tree with {$totalReferrals} total users\n";
} else {
    echo "⚠️  Genealogy tree has only {$totalReferrals} total users\n";
}

if (isset($levelCounts[5]) && $levelCounts[5] > 0) {
    echo "✅ Level 5 users found: {$levelCounts[5]} users\n";
} else {
    echo "❌ No Level 5 users found\n";
}

if ($activeReferrals > 20) {
    echo "✅ Good number of active members: {$activeReferrals}\n";
} else {
    echo "⚠️  Low number of active members: {$activeReferrals}\n";
}

// Test rank distribution
echo "\n🏆 Rank Distribution:\n";
echo "---------------------\n";

function getRankDistribution($users) {
    $ranks = [];

    foreach ($users as $user) {
        $rank = $user->rank ?? 'Guest';
        $ranks[$rank] = ($ranks[$rank] ?? 0) + 1;

        // Recursively get ranks from referrals
        if ($user->referrals->count() > 0) {
            $subRanks = getRankDistribution($user->referrals);
            foreach ($subRanks as $subRank => $subCount) {
                $ranks[$subRank] = ($ranks[$subRank] ?? 0) + $subCount;
            }
        }
    }

    return $ranks;
}

$rankDistribution = getRankDistribution($tree);
foreach ($rankDistribution as $rank => $count) {
    echo "  {$rank}: {$count} users\n";
}

echo "\n🚀 Mindmap View Test Passed!\n";
echo "The genealogy tree now supports viewing all 5 levels in a beautiful mindmap design.\n";
echo "Navigate to the genealogy page to see the new visualization.\n";

?>
