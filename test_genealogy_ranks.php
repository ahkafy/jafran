<?php

require_once 'vendor/autoload.php';

use App\Models\User;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing Genealogy Rank Display ===\n\n";

// Find a user with referrals
$testUser = User::where('email', 'test.member@example.com')->first();

if ($testUser) {
    echo "Testing genealogy for: {$testUser->name}\n";
    echo "Current rank: " . ($testUser->rank ?? 'Guest') . "\n";
    echo "Rank badge HTML: " . $testUser->getGenealogyRankBadge() . "\n\n";

    echo "Direct referrals:\n";
    echo str_repeat("-", 50) . "\n";

    foreach ($testUser->referrals as $referral) {
        echo "- {$referral->name} (" . ($referral->rank ?? 'Guest') . ")\n";
        echo "  Badge: " . $referral->getGenealogyRankBadge() . "\n";

        if ($referral->referrals->count() > 0) {
            echo "  Level 2 referrals:\n";
            foreach ($referral->referrals->take(3) as $level2) {
                echo "    - {$level2->name} (" . ($level2->rank ?? 'Guest') . ")\n";
            }
        }
        echo "\n";
    }
} else {
    echo "Test user not found. Let me check other users:\n\n";

    $usersWithReferrals = User::has('referrals')->with('referrals')->get();

    foreach ($usersWithReferrals as $user) {
        echo "User: {$user->name} (" . ($user->rank ?? 'Guest') . ")\n";
        echo "Badge: " . $user->getGenealogyRankBadge() . "\n";
        echo "Referrals: {$user->referrals->count()}\n\n";
    }
}

echo "✅ Genealogy rank display test completed!\n";
