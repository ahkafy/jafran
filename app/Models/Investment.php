<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'investment_package_id',
        'amount',
        'daily_return',
        'start_date',
        'end_date',
        'days_completed',
        'total_returned',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'daily_return' => 'decimal:2',
        'total_returned' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the user who made this investment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the investment package.
     */
    public function investmentPackage()
    {
        return $this->belongsTo(InvestmentPackage::class);
    }

    /**
     * Get daily returns for this investment.
     */
    public function dailyReturns()
    {
        return $this->hasMany(DailyReturn::class);
    }

    /**
     * Get commissions generated from this investment.
     */
    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    /**
     * Check if investment is still active.
     */
    public function isActive()
    {
        return $this->status === 'active' && Carbon::now()->lte($this->end_date);
    }

    /**
     * Get remaining days for this investment.
     */
    public function getRemainingDays()
    {
        if ($this->status !== 'active') {
            return 0;
        }

        $totalDays = $this->investmentPackage->return_days;
        return max(0, $totalDays - $this->days_completed);
    }

    /**
     * Get progress percentage.
     */
    public function getProgressPercentage()
    {
        $totalDays = $this->investmentPackage->return_days;
        return ($this->days_completed / $totalDays) * 100;
    }

    /**
     * Get expected total return.
     */
    public function getExpectedTotalReturn()
    {
        return $this->daily_return * $this->investmentPackage->return_days;
    }

    /**
     * Active investments scope.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Completed investments scope.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
