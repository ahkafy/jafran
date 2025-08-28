<?php

namespace App\Services;

use App\Models\Investment;
use App\Models\Commission;
use App\Models\User;
use App\Models\DailyReturn;
use Carbon\Carbon;

class MLMService
{
    /**
     * Commission percentages for each generation level
     * Level 1 (Direct): 10%
     * Level 2: 4%
     * Level 3: 3%
     * Level 4: 2%
     * Level 5: 2%
     */
    const COMMISSION_RATES = [
        1 => 10.00, // Direct sponsor - 10%
        2 => 4.00,  // Generation 1 - 4%
        3 => 3.00,  // Generation 2 - 3%
        4 => 2.00,  // Generation 3 - 2%
        5 => 2.00   // Generation 4 - 2%
    ];

    /**
     * Process investment and calculate MLM commissions
     */
    public function processInvestment(Investment $investment)
    {
        $user = $investment->user;
        $investmentAmount = $investment->amount;

        // Generate daily returns schedule
        $this->generateDailyReturns($investment);

        // Calculate and create commission records
        $this->calculateCommissions($user, $investment, $investmentAmount);

        return $investment;
    }

    /**
     * Generate daily returns for an investment
     */
    public function generateDailyReturns(Investment $investment)
    {
        $package = $investment->investmentPackage;
        $startDate = Carbon::parse($investment->start_date);
        $dailyReturnAmount = $investment->daily_return;

        for ($day = 1; $day <= $package->return_days; $day++) {
            $returnDate = $startDate->copy()->addDays($day - 1);

            DailyReturn::create([
                'investment_id' => $investment->id,
                'user_id' => $investment->user_id,
                'amount' => $dailyReturnAmount,
                'return_date' => $returnDate,
                'day_number' => $day,
                'status' => 'pending'
            ]);
        }
    }

    /**
     * Calculate MLM commissions for upline users
     */
    public function calculateCommissions(User $investor, Investment $investment, $investmentAmount)
    {
        $uplineUsers = $investor->getUplineUsers(5); // Get 5 levels of upline

        foreach ($uplineUsers as $uplineData) {
            $uplineUser = $uplineData['user'];
            $level = $uplineData['level'];

            if (!isset(self::COMMISSION_RATES[$level])) {
                continue;
            }

            $commissionPercentage = self::COMMISSION_RATES[$level];
            $commissionAmount = ($investmentAmount * $commissionPercentage) / 100;

            $commissionType = ($level === 1) ? 'direct' : 'generational';

            Commission::create([
                'user_id' => $uplineUser->id,
                'from_user_id' => $investor->id,
                'investment_id' => $investment->id,
                'amount' => $commissionAmount,
                'percentage' => $commissionPercentage,
                'level' => $level,
                'type' => $commissionType,
                'status' => 'pending'
            ]);
        }
    }

    /**
     * Process daily returns for a specific date
     */
    public function processDailyReturns($date = null)
    {
        $processDate = $date ? Carbon::parse($date) : Carbon::today();

        $pendingReturns = DailyReturn::where('return_date', $processDate)
            ->where('status', 'pending')
            ->with(['investment', 'user'])
            ->get();

        $processedCount = 0;

        foreach ($pendingReturns as $dailyReturn) {
            if ($dailyReturn->investment->isActive()) {
                $dailyReturn->markAsProcessed();
                $processedCount++;
            }
        }

        return $processedCount;
    }

    /**
     * Process pending commissions
     */
    public function processCommissions()
    {
        $pendingCommissions = Commission::where('status', 'pending')
            ->with(['user', 'fromUser', 'investment'])
            ->get();

        $processedCount = 0;

        foreach ($pendingCommissions as $commission) {
            // Check if the investment is still active
            if ($commission->investment->status === 'active') {
                $commission->markAsPaid();
                $processedCount++;
            }
        }

        return $processedCount;
    }

    /**
     * Get user's MLM statistics
     */
    public function getUserMLMStats(User $user)
    {
        $directReferrals = $user->referrals()->count();
        $totalTeam = $user->getTotalTeamSize();
        $totalInvestment = $user->getTotalInvestmentAmount();
        $totalCommissions = $user->getTotalCommissions();
        $activeInvestments = $user->activeInvestments()->count();

        // Get team investments by level
        $teamInvestments = [];
        for ($level = 1; $level <= 5; $level++) {
            $teamInvestments[$level] = $this->getTeamInvestmentsByLevel($user, $level);
        }

        return [
            'direct_referrals' => $directReferrals,
            'total_team' => $totalTeam,
            'total_investment' => $totalInvestment,
            'total_commissions' => $totalCommissions,
            'active_investments' => $activeInvestments,
            'team_investments' => $teamInvestments,
            'wallet_balance' => $user->wallet_balance,
            'commission_balance' => $user->commission_balance,
        ];
    }

    /**
     * Get team investments by level
     */
    private function getTeamInvestmentsByLevel(User $user, $level, $currentLevel = 0)
    {
        if ($currentLevel >= $level) {
            return $user->getTotalInvestmentAmount();
        }

        $total = 0;
        foreach ($user->referrals as $referral) {
            if ($currentLevel + 1 === $level) {
                $total += $referral->getTotalInvestmentAmount();
            } else {
                $total += $this->getTeamInvestmentsByLevel($referral, $level, $currentLevel + 1);
            }
        }

        return $total;
    }

    /**
     * Create investment with MLM processing
     */
    public function createInvestment(User $user, $packageId, $amount)
    {
        $package = \App\Models\InvestmentPackage::findOrFail($packageId);

        // Check if user has sufficient balance
        if ($user->wallet_balance < $amount) {
            throw new \Exception('Insufficient wallet balance');
        }

        // Deduct from user's wallet
        $user->decrement('wallet_balance', $amount);

        // Create investment
        $investment = Investment::create([
            'user_id' => $user->id,
            'investment_package_id' => $packageId,
            'amount' => $amount,
            'daily_return' => $package->getDailyReturnAmount(),
            'start_date' => Carbon::today(),
            'end_date' => Carbon::today()->addDays($package->return_days - 1),
            'status' => 'active'
        ]);

        // Process MLM commissions
        $this->processInvestment($investment);

        return $investment;
    }

    /**
     * Get genealogy tree for a user
     */
    public function getGenealogyTree(User $user, $maxDepth = 4)
    {
        return $this->buildGenealogyNode($user, 0, $maxDepth);
    }

    /**
     * Build genealogy tree node
     */
    private function buildGenealogyNode(User $user, $currentDepth, $maxDepth)
    {
        $node = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'referral_code' => $user->referral_code,
            'total_investment' => $user->getTotalInvestmentAmount(),
            'status' => $user->status,
            'level' => $currentDepth,
            'children' => []
        ];

        if ($currentDepth < $maxDepth) {
            foreach ($user->referrals as $referral) {
                $node['children'][] = $this->buildGenealogyNode($referral, $currentDepth + 1, $maxDepth);
            }
        }

        return $node;
    }
}
