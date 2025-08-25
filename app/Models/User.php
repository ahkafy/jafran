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
        'status',
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
     * Get all users in the upline (sponsors) up to 4 levels.
     */
    public function getUplineUsers($maxLevels = 4)
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
}
