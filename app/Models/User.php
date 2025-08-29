<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'referral_code',
        'sponsor_id',
        'phone',
        'address',
        'wallet_balance',
        'commission_balance',
        'investment_balance',
        'return_balance',
        'withdrawable_balance',
        'pending_withdrawal_amount',
        'last_withdrawal_processed_at',
        'status',
        'rank',
        'rank_achieved_at',
        'total_investment',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'wallet_balance' => 'decimal:2',
            'commission_balance' => 'decimal:2',
            'investment_balance' => 'decimal:2',
            'return_balance' => 'decimal:2',
            'withdrawable_balance' => 'decimal:2',
            'pending_withdrawal_amount' => 'decimal:2',
            'total_investment' => 'decimal:2',
            'rank_achieved_at' => 'datetime',
            'last_withdrawal_processed_at' => 'datetime',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (!$user->referral_code) {
                $user->referral_code = strtoupper(Str::random(8));
            }
        });
    }

    /**
     * Get the sponsor (upline) user.
     */
    public function sponsor()
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }

    /**
     * Get direct referrals (downline) users.
     */
    public function referrals()
    {
        return $this->hasMany(User::class, 'sponsor_id');
    }

    /**
     * Alias for referrals for compatibility.
     */
    public function children()
    {
        return $this->referrals();
    }

    /**
     * Get all investments made by the user.
     */
    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    /**
     * Get commissions earned by the user.
     */
    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    /**
     * Get daily returns for the user.
     */
    public function dailyReturns()
    {
        return $this->hasMany(DailyReturn::class);
    }

    /**
     * Get wallet transactions for the user.
     */
    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Get gift cards created by the user.
     */
    public function createdGiftCards()
    {
        return $this->hasMany(GiftCard::class, 'created_by');
    }

    /**
     * Get gift cards redeemed by the user.
     */
    public function redeemedGiftCards()
    {
        return $this->hasMany(GiftCard::class, 'redeemed_by');
    }

    /**
     * Get withdrawal requests made by the user.
     */
    public function withdrawalRequests()
    {
        return $this->hasMany(WithdrawalRequest::class);
    }

    /**
     * Get all users in the upline (sponsors) up to 5 levels.
     */
    public function getUplineUsers($maxLevels = 5)
    {
        $upline = [];
        $currentUser = $this;
        $level = 1;

        while ($currentUser->sponsor && $level <= $maxLevels) {
            $upline[] = [
                'user' => $currentUser->sponsor,
                'level' => $level
            ];
            $currentUser = $currentUser->sponsor;
            $level++;
        }

        return $upline;
    }

    /**
     * Get total team size (all downline users).
     */
    public function getTotalTeamSize()
    {
        $count = 0;
        $directReferrals = $this->referrals;

        foreach ($directReferrals as $referral) {
            $count += 1 + $referral->getTotalTeamSize();
        }

        return $count;
    }

    /**
     * Get active investments.
     */
    public function activeInvestments()
    {
        return $this->investments()->where('status', 'active');
    }

    /**
     * Get total investment amount.
     */
    public function getTotalInvestmentAmount()
    {
        return $this->investments()->sum('amount');
    }

    /**
     * Get total commissions earned.
     */
    public function getTotalCommissions()
    {
        return $this->commissions()->where('status', 'paid')->sum('amount');
    }

    /**
     * Get rank information with styling.
     */
    public function getRankInfo()
    {
        $rankingService = app(\App\Services\RankingService::class);
        return $rankingService->getRankInfo($this->rank ?? 'Guest');
    }

    /**
     * Get rank badge HTML.
     */
    public function getRankBadge()
    {
        $rankInfo = $this->getRankInfo();
        return '<span class="badge bg-' . $rankInfo['color'] . '">
                    <i class="fas fa-' . $rankInfo['icon'] . ' me-1"></i>' .
                    $rankInfo['name'] .
                '</span>';
    }

    /**
     * Get rank badge HTML for genealogy tree (smaller).
     */
    public function getGenealogyRankBadge()
    {
        $rankInfo = $this->getRankInfo();
        return '<span class="badge bg-' . $rankInfo['color'] . ' genealogy-rank-badge">
                    <i class="fas fa-' . $rankInfo['icon'] . ' me-1"></i>' .
                    $rankInfo['name'] .
                '</span>';
    }

    /**
     * Update user's rank based on current achievements.
     */
    public function updateRank()
    {
        $rankingService = app(\App\Services\RankingService::class);
        return $rankingService->updateUserRank($this);
    }

    /**
     * Get progress to next rank.
     */
    public function getRankProgress()
    {
        $rankingService = app(\App\Services\RankingService::class);
        return $rankingService->getRankProgress($this);
    }

    /**
     * Get total withdrawable balance (commission + returns)
     */
    public function getTotalWithdrawableBalance()
    {
        return $this->withdrawable_balance;
    }

    /**
     * Get total available balance for investment
     */
    public function getInvestmentAvailableBalance()
    {
        return $this->investment_balance;
    }

    /**
     * Check if user can make withdrawal
     */
    public function canMakeWithdrawal()
    {
        return $this->withdrawable_balance > 0;
    }

    /**
     * Check if user can make investment
     */
    public function canMakeInvestment($amount = 0)
    {
        return $this->investment_balance >= $amount;
    }

    /**
     * Get wallet summary for display
     */
    public function getWalletSummary()
    {
        $walletService = app(\App\Services\WalletService::class);
        return $walletService->getWalletSummary($this);
    }

    /**
     * Get next withdrawal processing date info
     */
    public function getNextWithdrawalDate()
    {
        $walletService = app(\App\Services\WalletService::class);
        return $walletService->getNextProcessingDate();
    }
}
