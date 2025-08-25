<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'category',
        'amount',
        'payment_method',
        'payment_reference',
        'description',
        'status',
        'metadata',
        'processed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'processed_at' => 'datetime'
    ];

    const TYPE_CREDIT = 'credit';
    const TYPE_DEBIT = 'debit';

    const CATEGORY_DEPOSIT = 'deposit';
    const CATEGORY_WITHDRAWAL = 'withdrawal';
    const CATEGORY_INVESTMENT = 'investment';
    const CATEGORY_RETURN = 'return';
    const CATEGORY_COMMISSION = 'commission';
    const CATEGORY_GIFT_CARD = 'gift_card';
    const CATEGORY_TRANSFER = 'transfer';

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_METHOD_STRIPE = 'stripe';
    const PAYMENT_METHOD_PAYPAL = 'paypal';
    const PAYMENT_METHOD_BANK_TRANSFER = 'bank_transfer';
    const PAYMENT_METHOD_GIFT_CARD = 'gift_card';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeCredits($query)
    {
        return $query->where('type', self::TYPE_CREDIT);
    }

    public function scopeDebits($query)
    {
        return $query->where('type', self::TYPE_DEBIT);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    public function getTypeIconAttribute()
    {
        return $this->type === self::TYPE_CREDIT ? 'fas fa-arrow-up text-success' : 'fas fa-arrow-down text-danger';
    }

    public function getCategoryIconAttribute()
    {
        return match($this->category) {
            self::CATEGORY_DEPOSIT => 'fas fa-plus-circle',
            self::CATEGORY_WITHDRAWAL => 'fas fa-minus-circle',
            self::CATEGORY_INVESTMENT => 'fas fa-chart-line',
            self::CATEGORY_RETURN => 'fas fa-coins',
            self::CATEGORY_COMMISSION => 'fas fa-percentage',
            self::CATEGORY_GIFT_CARD => 'fas fa-gift',
            self::CATEGORY_TRANSFER => 'fas fa-exchange-alt',
            default => 'fas fa-circle'
        };
    }
}
