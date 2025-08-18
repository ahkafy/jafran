<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            InvestmentPackageSeeder::class,
        ]);

        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@mlm.com',
            'password' => bcrypt('password'),
            'referral_code' => 'ADMIN001',
            'wallet_balance' => 1000.00,
            'commission_balance' => 0,
            'status' => 'active',
        ]);

        // Create test user
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'referral_code' => 'TEST001',
            'wallet_balance' => 100.00,
            'commission_balance' => 0,
            'status' => 'active',
        ]);
    }
}
