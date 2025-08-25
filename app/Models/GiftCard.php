<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GiftCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'created_by',
        'redeemed_by',
        'amount',
        'balance',
        'status',
        'message',
        'expires_at',
        'redeemed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance' => 'decimal:2',
        'expires_at' => 'datetime',
        'redeemed_at' => 'datetime'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_REDEEMED = 'redeemed';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($giftCard) {
            if (empty($giftCard->code)) {
                $giftCard->code = self::generateUniqueCode();
            }
            if (empty($giftCard->balance)) {
                $giftCard->balance = $giftCard->amount;
            }
        });
    }

    public static function generateUniqueCode()
    {
        do {
            $code = 'GC-' . strtoupper(Str::random(12));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function redeemer()
    {
        return $this->belongsTo(User::class, 'redeemed_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeRedeemed($query)
    {
        return $query->where('status', self::STATUS_REDEEMED);
    }

    public function isRedeemable()
    {
        return $this->status === self::STATUS_ACTIVE &&
               $this->balance > 0 &&
               ($this->expires_at === null || $this->expires_at->isFuture());
    }

    public function redeem($user, $amount = null)
    {
        if (!$this->isRedeemable()) {
            return false;
        }

        $redeemAmount = $amount ?? $this->balance;

        if ($redeemAmount > $this->balance) {
            return false;
        }

        $this->balance -= $redeemAmount;

        if ($this->balance <= 0) {
            $this->status = self::STATUS_REDEEMED;
            $this->redeemed_by = $user->id;
            $this->redeemed_at = now();
        }

        $this->save();

        return $redeemAmount;
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    public function getFormattedBalanceAttribute()
    {
        return number_format($this->balance, 2);
    }
}
