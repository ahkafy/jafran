<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'investment_id',
        'user_id',
        'amount',
        'return_date',
        'day_number',
        'status',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'return_date' => 'date',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the investment this return belongs to.
     */
    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }

    /**
     * Get the user receiving this return.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Processed returns scope.
     */
    public function scopeProcessed($query)
    {
        return $query->where('status', 'processed');
    }

    /**
     * Pending returns scope.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Mark return as processed.
     */
    public function markAsProcessed()
    {
        $this->update([
            'status' => 'processed',
            'processed_at' => now(),
        ]);

        // Add to user's wallet balance
        $this->user->increment('wallet_balance', $this->amount);

        // Update investment progress
        $this->investment->increment('days_completed');
        $this->investment->increment('total_returned', $this->amount);

        // Check if investment is completed
        if ($this->investment->days_completed >= $this->investment->investmentPackage->return_days) {
            $this->investment->update(['status' => 'completed']);
        }
    }
}
