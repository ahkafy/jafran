<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'balance_type',
        'commission_amount',
        'returns_amount',
        'scheduled_processing_date',
        'processing_cycle',
        'balance_breakdown',
        'method',
        'processing_fee_percentage',
        'processing_fee_amount',
        'net_amount',
        'bank_name',
        'account_holder_name',
        'account_number',
        'routing_number',
        'swift_code',
        'bank_address',
        'bank_city',
        'bank_state',
        'bank_zip_code',
        'bank_country',
        'account_type',
        'mbook_name',
        'mbook_country',
        'mbook_currency',
        'mbook_wallet_id',
        'status',
        'notes',
        'admin_notes',
        'reference_number',
        'processed_at',
        'processed_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'returns_amount' => 'decimal:2',
        'processing_fee_percentage' => 'decimal:2',
        'processing_fee_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'scheduled_processing_date' => 'date',
        'processed_at' => 'datetime',
        'balance_breakdown' => 'array'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_PROCESSED = 'processed';
    const STATUS_REJECTED = 'rejected';

    const METHOD_BANK = 'bank';
    const METHOD_MBOOK = 'mbook';

    const ACCOUNT_TYPE_CHECKING = 'checking';
    const ACCOUNT_TYPE_SAVINGS = 'savings';

    // Processing fee rates
    const US_BANK_FEE_RATE = 2.00; // 2%
    const OTHER_BANK_FEE_RATE = 10.00; // 10%
    const MBOOK_FEE_RATE = 5.00; // 5% for MBook

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeProcessed($query)
    {
        return $query->where('status', self::STATUS_PROCESSED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'badge bg-warning',
            self::STATUS_APPROVED => 'badge bg-info',
            self::STATUS_PROCESSED => 'badge bg-success',
            self::STATUS_REJECTED => 'badge bg-danger',
            default => 'badge bg-secondary'
        };
    }

    public function getMaskedAccountNumberAttribute()
    {
        if ($this->method === self::METHOD_MBOOK) {
            return '****' . substr($this->mbook_wallet_id ?? '', -4);
        }

        if (strlen($this->account_number) <= 4) {
            return $this->account_number;
        }

        return '****' . substr($this->account_number, -4);
    }

    public function getFormattedNetAmountAttribute()
    {
        return number_format($this->net_amount, 2);
    }

    public function getFormattedProcessingFeeAttribute()
    {
        return number_format($this->processing_fee_amount, 2);
    }

    public function getDisplayMethodAttribute()
    {
        return match($this->method) {
            self::METHOD_BANK => 'Bank Transfer',
            self::METHOD_MBOOK => 'MBook Wallet',
            default => ucfirst($this->method)
        };
    }

    public function getMethodDetailsAttribute()
    {
        if ($this->method === self::METHOD_MBOOK) {
            return "{$this->mbook_name} ({$this->mbook_country})";
        }

        $country = $this->bank_country ?: 'US';
        return "{$this->bank_name} ({$country})";
    }

    /**
     * Calculate processing fee based on withdrawal method and bank country
     */
    public static function calculateProcessingFee($amount, $method, $bankCountry = 'US')
    {
        $feeRate = match($method) {
            self::METHOD_MBOOK => self::MBOOK_FEE_RATE,
            self::METHOD_BANK => $bankCountry === 'US' ? self::US_BANK_FEE_RATE : self::OTHER_BANK_FEE_RATE,
            default => self::OTHER_BANK_FEE_RATE
        };

        $feeAmount = ($amount * $feeRate) / 100;
        $netAmount = $amount - $feeAmount;

        return [
            'fee_rate' => $feeRate,
            'fee_amount' => round($feeAmount, 2),
            'net_amount' => round($netAmount, 2)
        ];
    }
}
