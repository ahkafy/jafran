<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InvestmentPackage;

class InvestmentPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InvestmentPackage::create([
            'name' => 'Starter Package',
            'description' => 'Perfect for beginners. Invest $5 and earn 1% daily return for 200 days.',
            'amount' => 5.00,
            'daily_return_percentage' => 1.00,
            'return_days' => 200,
            'is_active' => true,
        ]);

        InvestmentPackage::create([
            'name' => 'Basic Package',
            'description' => 'Great for steady growth. Invest $10 and earn 1% daily return for 200 days.',
            'amount' => 10.00,
            'daily_return_percentage' => 1.00,
            'return_days' => 200,
            'is_active' => true,
        ]);

        InvestmentPackage::create([
            'name' => 'Standard Package',
            'description' => 'Ideal for regular investors. Invest $25 and earn 1% daily return for 200 days.',
            'amount' => 25.00,
            'daily_return_percentage' => 1.00,
            'return_days' => 200,
            'is_active' => true,
        ]);

        InvestmentPackage::create([
            'name' => 'Premium Package',
            'description' => 'For serious investors. Invest $50 and earn 1% daily return for 200 days.',
            'amount' => 50.00,
            'daily_return_percentage' => 1.00,
            'return_days' => 200,
            'is_active' => true,
        ]);

        InvestmentPackage::create([
            'name' => 'Gold Package',
            'description' => 'Maximum returns. Invest $100 and earn 1% daily return for 200 days.',
            'amount' => 100.00,
            'daily_return_percentage' => 1.00,
            'return_days' => 200,
            'is_active' => true,
        ]);
    }
}
