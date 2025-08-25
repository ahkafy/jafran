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
        'method',
        'bank_name',
        'account_holder_name',
        'account_number',
        'routing_number',
        'swift_code',
        'bank_address',
        'bank_city',
        'bank_state',
        'bank_zip_code',
        'account_type',
        'status',
        'notes',
        'admin_notes',
        'reference_number',
        'processed_at',
        'processed_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_PROCESSED = 'processed';
    const STATUS_REJECTED = 'rejected';

    const METHOD_EFT = 'eft';

    const ACCOUNT_TYPE_CHECKING = 'checking';
    const ACCOUNT_TYPE_SAVINGS = 'savings';

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
        if (strlen($this->account_number) <= 4) {
            return $this->account_number;
        }

        return '****' . substr($this->account_number, -4);
    }
}
