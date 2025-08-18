<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'amount',
        'daily_return_percentage',
        'return_days',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'daily_return_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get investments for this package.
     */
    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    /**
     * Calculate daily return amount.
     */
    public function getDailyReturnAmount()
    {
        return ($this->amount * $this->daily_return_percentage) / 100;
    }

    /**
     * Calculate total return amount.
     */
    public function getTotalReturnAmount()
    {
        return $this->getDailyReturnAmount() * $this->return_days;
    }

    /**
     * Get active packages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
