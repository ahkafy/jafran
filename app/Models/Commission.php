<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_user_id',
        'investment_id',
        'amount',
        'percentage',
        'level',
        'type',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'percentage' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the user receiving the commission.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who generated the commission.
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Get the investment that generated this commission.
     */
    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }

    /**
     * Paid commissions scope.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Pending commissions scope.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Direct commissions scope.
     */
    public function scopeDirect($query)
    {
        return $query->where('type', 'direct');
    }

    /**
     * Generational commissions scope.
     */
    public function scopeGenerational($query)
    {
        return $query->where('type', 'generational');
    }

    /**
     * Mark commission as paid.
     */
    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Add to user's commission balance
        $this->user->increment('commission_balance', $this->amount);
    }
}
