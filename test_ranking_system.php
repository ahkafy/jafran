<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Services\RankingService;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== User Ranking System Test ===\n\n";

$rankingService = new RankingService();

// Get all users with their current ranks
$users = User::all();

echo "Current User Rankings:\n";
echo str_repeat("-", 80) . "\n";
echo sprintf("%-20s %-15s %-15s %-15s %-10s\n",
    "Name", "Rank", "Total Investment", "Direct Referrals", "Team Size");
echo str_repeat("-", 80) . "\n";

foreach ($users as $user) {
    $directReferrals = $user->referrals()->count();
    $teamSize = $user->getTotalTeamSize();

    echo sprintf("%-20s %-15s $%-14.2f %-15d %-10d\n",
        substr($user->name, 0, 19),
        $user->rank ?? 'Guest',
        $user->total_investment ?? 0,
        $directReferrals,
        $teamSize
    );
}

echo "\n" . str_repeat("=", 80) . "\n";

// Show rank requirements
echo "\nRank Requirements:\n";
echo str_repeat("-", 80) . "\n";

foreach ($rankingService->getAllRanks() as $rankName => $rankInfo) {
    echo "📊 {$rankName}: {$rankInfo['requirements']}\n";
}

echo "\n" . str_repeat("=", 80) . "\n";

// Show rank progression for a specific user
if ($users->count() > 0) {
    $testUser = $users->first();
    echo "\nRank Progress for {$testUser->name}:\n";
    echo str_repeat("-", 50) . "\n";

    $progress = $testUser->getRankProgress();

    if ($progress['next_rank']) {
        echo "Current Rank: {$testUser->rank}\n";
        echo "Next Rank: {$progress['next_rank']}\n\n";
        echo "Requirements Progress:\n";

        foreach ($progress['progress'] as $requirement => $data) {
            $percentage = min(100, ($data['current'] / $data['required']) * 100);
            $status = $data['met'] ? '✅' : '❌';

            echo "  {$status} " . ucwords(str_replace('_', ' ', $requirement)) . ": ";
            echo "{$data['current']}/{$data['required']} (" . number_format($percentage, 1) . "%)\n";
        }
    } else {
        echo "🏆 {$testUser->name} has achieved the maximum rank!\n";
    }
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "✅ Ranking system test completed!\n";
