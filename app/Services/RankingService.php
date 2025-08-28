<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class RankingService
{
    /**
     * Rank definitions and requirements
     */
    const RANKS = [
        'Guest' => [
            'name' => 'Guest',
            'min_investment' => 0,
            'color' => 'secondary',
            'icon' => 'user',
            'requirements' => 'No investment required'
        ],
        'Member' => [
            'name' => 'Member',
            'min_investment' => 5,
            'color' => 'success',
            'icon' => 'user-check',
            'requirements' => 'Invest at least $5'
        ],
        'Counsellor' => [
            'name' => 'Counsellor',
            'min_investment' => 5,
            'min_direct_referrals' => 5,
            'color' => 'info',
            'icon' => 'users',
            'requirements' => 'Member + 5 direct sponsors'
        ],
        'Leader' => [
            'name' => 'Leader',
            'min_investment' => 5,
            'min_counsellors' => 3,
            'color' => 'warning',
            'icon' => 'crown',
            'requirements' => 'Counsellor + 3 Counsellors in team'
        ],
        'Trainer' => [
            'name' => 'Trainer',
            'min_investment' => 5,
            'min_leaders' => 2,
            'color' => 'danger',
            'icon' => 'award',
            'requirements' => 'Leader + 2 Leaders in network'
        ],
        'Senior Trainer' => [
            'name' => 'Senior Trainer',
            'min_investment' => 5,
            'min_trainers' => 2,
            'color' => 'dark',
            'icon' => 'trophy',
            'requirements' => 'Trainer + 2 Trainers in network'
        ]
    ];

    /**
     * Calculate and update user's rank
     */
    public function updateUserRank(User $user)
    {
        $newRank = $this->calculateUserRank($user);

        if ($user->rank !== $newRank) {
            $oldRank = $user->rank;
            $user->update([
                'rank' => $newRank,
                'rank_achieved_at' => now()
            ]);

            // Log rank advancement
            \Log::info("User {$user->id} ({$user->name}) ranked up from {$oldRank} to {$newRank}");

            return true; // Rank changed
        }

        return false; // No rank change
    }

    /**
     * Calculate user's rank based on requirements
     */
    public function calculateUserRank(User $user)
    {
        // Update total investment
        $totalInvestment = $user->investments()->sum('amount');
        $user->update(['total_investment' => $totalInvestment]);

        // Check rank requirements in descending order
        if ($this->qualifiesForSeniorTrainer($user)) {
            return 'Senior Trainer';
        }

        if ($this->qualifiesForTrainer($user)) {
            return 'Trainer';
        }

        if ($this->qualifiesForLeader($user)) {
            return 'Leader';
        }

        if ($this->qualifiesForCounsellor($user)) {
            return 'Counsellor';
        }

        if ($this->qualifiesForMember($user)) {
            return 'Member';
        }

        return 'Guest';
    }

    /**
     * Check if user qualifies for Member rank
     */
    private function qualifiesForMember(User $user)
    {
        return $user->total_investment >= 5;
    }

    /**
     * Check if user qualifies for Counsellor rank
     */
    private function qualifiesForCounsellor(User $user)
    {
        if (!$this->qualifiesForMember($user)) {
            return false;
        }

        $directReferrals = $user->referrals()->count();
        return $directReferrals >= 5;
    }

    /**
     * Check if user qualifies for Leader rank
     */
    private function qualifiesForLeader(User $user)
    {
        if (!$this->qualifiesForCounsellor($user)) {
            return false;
        }

        $counsellorsInTeam = $this->countRankInTeam($user, 'Counsellor');
        return $counsellorsInTeam >= 3;
    }

    /**
     * Check if user qualifies for Trainer rank
     */
    private function qualifiesForTrainer(User $user)
    {
        if (!$this->qualifiesForLeader($user)) {
            return false;
        }

        $leadersInNetwork = $this->countRankInTeam($user, 'Leader');
        return $leadersInNetwork >= 2;
    }

    /**
     * Check if user qualifies for Senior Trainer rank
     */
    private function qualifiesForSeniorTrainer(User $user)
    {
        if (!$this->qualifiesForTrainer($user)) {
            return false;
        }

        $trainersInNetwork = $this->countRankInTeam($user, 'Trainer');
        return $trainersInNetwork >= 2;
    }

    /**
     * Count users with specific rank in the team (all levels)
     */
    private function countRankInTeam(User $user, string $rank)
    {
        return $this->getTeamMembers($user)->where('rank', $rank)->count();
    }

    /**
     * Get all team members (recursive)
     */
    private function getTeamMembers(User $user)
    {
        $teamMembers = collect();
        $this->addTeamMembersRecursive($user, $teamMembers);
        return $teamMembers;
    }

    /**
     * Recursively add team members
     */
    private function addTeamMembersRecursive(User $user, &$teamMembers, $depth = 0)
    {
        // Prevent infinite recursion
        if ($depth > 10) {
            return;
        }

        $directReferrals = $user->referrals;

        foreach ($directReferrals as $referral) {
            if (!$teamMembers->contains('id', $referral->id)) {
                $teamMembers->push($referral);
                $this->addTeamMembersRecursive($referral, $teamMembers, $depth + 1);
            }
        }
    }

    /**
     * Update ranks for all users
     */
    public function updateAllUserRanks()
    {
        $users = User::all();
        $updated = 0;

        foreach ($users as $user) {
            if ($this->updateUserRank($user)) {
                $updated++;
            }
        }

        return $updated;
    }

    /**
     * Get rank information
     */
    public function getRankInfo(string $rank)
    {
        return self::RANKS[$rank] ?? self::RANKS['Guest'];
    }

    /**
     * Get all ranks
     */
    public function getAllRanks()
    {
        return self::RANKS;
    }

    /**
     * Get next rank for user
     */
    public function getNextRank(User $user)
    {
        $currentRank = $user->rank;
        $ranks = array_keys(self::RANKS);
        $currentIndex = array_search($currentRank, $ranks);

        if ($currentIndex !== false && $currentIndex < count($ranks) - 1) {
            return $ranks[$currentIndex + 1];
        }

        return null; // Already at highest rank
    }

    /**
     * Get rank progress for user
     */
    public function getRankProgress(User $user)
    {
        $nextRank = $this->getNextRank($user);

        if (!$nextRank) {
            return ['progress' => 100, 'next_rank' => null, 'requirements_met' => []];
        }

        $progress = [];
        $requirements = self::RANKS[$nextRank];

        // Check investment requirement
        if (isset($requirements['min_investment'])) {
            $progress['investment'] = [
                'current' => $user->total_investment,
                'required' => $requirements['min_investment'],
                'met' => $user->total_investment >= $requirements['min_investment']
            ];
        }

        // Check direct referrals requirement
        if (isset($requirements['min_direct_referrals'])) {
            $directReferrals = $user->referrals()->count();
            $progress['direct_referrals'] = [
                'current' => $directReferrals,
                'required' => $requirements['min_direct_referrals'],
                'met' => $directReferrals >= $requirements['min_direct_referrals']
            ];
        }

        // Check team rank requirements
        foreach (['min_counsellors', 'min_leaders', 'min_trainers'] as $req) {
            if (isset($requirements[$req])) {
                $rankName = ucfirst(str_replace('min_', '', $req));
                $rankName = rtrim($rankName, 's'); // Remove 's' from plural

                $count = $this->countRankInTeam($user, $rankName);
                $progress[str_replace('min_', '', $req)] = [
                    'current' => $count,
                    'required' => $requirements[$req],
                    'met' => $count >= $requirements[$req]
                ];
            }
        }

        return [
            'next_rank' => $nextRank,
            'progress' => $progress
        ];
    }
}
