<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Investment;
use App\Models\InvestmentPackage;
use App\Services\RankingService;
use Illuminate\Support\Facades\Hash;

class FiveLevelMLMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Creating 5-Level MLM Structure under Abdullah Hel Kafy...');

        // Find Abdullah Hel Kafy (using first one found)
        $kafy = User::where('name', 'like', '%Kafy%')->first();

        if (!$kafy) {
            $this->command->error('❌ Kafy user not found!');
            return;
        }

        $this->command->info("✅ Found root user: {$kafy->name} (ID: {$kafy->id})");

        // Get investment package for creating investments
        $package = InvestmentPackage::first();
        if (!$package) {
            $this->command->warn('⚠️ No investment package found. Creating basic package...');
            $package = InvestmentPackage::create([
                'name' => 'MLM Test Package',
                'min_amount' => 5,
                'max_amount' => 1000,
                'daily_return_rate' => 0.5,
                'return_days' => 30,
                'status' => 'active'
            ]);
        }

        // Clear existing test users if they exist
        $this->clearExistingTestUsers();

        // Create 5-level structure
        $this->createLevel1Users($kafy, $package);

        // Update all ranks
        $rankingService = new RankingService();
        $updated = $rankingService->updateAllUserRanks();
        $this->command->info("🏆 Updated ranks for {$updated} users");

        $this->command->info('🎉 5-Level MLM structure created successfully!');
        $this->displayStructure($kafy);
    }

    /**
     * Clear existing test users to avoid duplicates
     */
    private function clearExistingTestUsers()
    {
        $testEmails = [
            'level1.user1@test.com', 'level1.user2@test.com', 'level1.user3@test.com',
            'level2.user1@test.com', 'level2.user2@test.com', 'level2.user3@test.com',
            'level3.user1@test.com', 'level3.user2@test.com', 'level3.user3@test.com',
            'level4.user1@test.com', 'level4.user2@test.com', 'level4.user3@test.com',
            'level5.user1@test.com', 'level5.user2@test.com', 'level5.user3@test.com',
        ];

        foreach ($testEmails as $email) {
            User::where('email', $email)->delete();
        }

        $this->command->info('🧹 Cleared existing test users');
    }

    /**
     * Create Level 1 users (Direct referrals under Kafy)
     */
    private function createLevel1Users(User $kafy, InvestmentPackage $package)
    {
        $this->command->info('📝 Creating Level 1 users...');

        for ($i = 1; $i <= 3; $i++) {
            $level1User = User::create([
                'name' => "Level 1 User {$i}",
                'email' => "level1.user{$i}@test.com",
                'password' => Hash::make('password'),
                'sponsor_id' => $kafy->id,
                'wallet_balance' => 100.00,
                'status' => 'active'
            ]);

            // Give investment to qualify for Member rank
            $this->createInvestment($level1User, $package, rand(10, 25));

            $this->command->info("  ✅ Created {$level1User->name}");

            // Create Level 2 under this Level 1 user
            $this->createLevel2Users($level1User, $package, $i);
        }
    }

    /**
     * Create Level 2 users
     */
    private function createLevel2Users(User $parentUser, InvestmentPackage $package, int $parentIndex)
    {
        for ($i = 1; $i <= 2; $i++) {
            $level2User = User::create([
                'name' => "Level 2 User {$parentIndex}.{$i}",
                'email' => "level2.user{$parentIndex}{$i}@test.com",
                'password' => Hash::make('password'),
                'sponsor_id' => $parentUser->id,
                'wallet_balance' => 75.00,
                'status' => 'active'
            ]);

            // Some get investments to qualify for Member rank
            if ($i <= 1) {
                $this->createInvestment($level2User, $package, rand(8, 15));
            }

            $this->command->info("    ✅ Created {$level2User->name}");

            // Create Level 3 under this Level 2 user
            $this->createLevel3Users($level2User, $package, $parentIndex, $i);
        }
    }

    /**
     * Create Level 3 users
     */
    private function createLevel3Users(User $parentUser, InvestmentPackage $package, int $level1Index, int $level2Index)
    {
        for ($i = 1; $i <= 2; $i++) {
            $level3User = User::create([
                'name' => "Level 3 User {$level1Index}.{$level2Index}.{$i}",
                'email' => "level3.user{$level1Index}{$level2Index}{$i}@test.com",
                'password' => Hash::make('password'),
                'sponsor_id' => $parentUser->id,
                'wallet_balance' => 50.00,
                'status' => 'active'
            ]);

            // Some get investments
            if (rand(1, 2) == 1) {
                $this->createInvestment($level3User, $package, rand(5, 12));
            }

            $this->command->info("      ✅ Created {$level3User->name}");

            // Create Level 4 under this Level 3 user
            $this->createLevel4Users($level3User, $package, $level1Index, $level2Index, $i);
        }
    }

    /**
     * Create Level 4 users
     */
    private function createLevel4Users(User $parentUser, InvestmentPackage $package, int $level1Index, int $level2Index, int $level3Index)
    {
        for ($i = 1; $i <= 2; $i++) {
            $level4User = User::create([
                'name' => "Level 4 User {$level1Index}.{$level2Index}.{$level3Index}.{$i}",
                'email' => "level4.user{$level1Index}{$level2Index}{$level3Index}{$i}@test.com",
                'password' => Hash::make('password'),
                'sponsor_id' => $parentUser->id,
                'wallet_balance' => 30.00,
                'status' => 'active'
            ]);

            // Fewer get investments at this level
            if (rand(1, 3) == 1) {
                $this->createInvestment($level4User, $package, rand(5, 10));
            }

            $this->command->info("        ✅ Created {$level4User->name}");

            // Create Level 5 under this Level 4 user
            $this->createLevel5Users($level4User, $package, $level1Index, $level2Index, $level3Index, $i);
        }
    }

    /**
     * Create Level 5 users
     */
    private function createLevel5Users(User $parentUser, InvestmentPackage $package, int $level1Index, int $level2Index, int $level3Index, int $level4Index)
    {
        for ($i = 1; $i <= 2; $i++) {
            $level5User = User::create([
                'name' => "Level 5 User {$level1Index}.{$level2Index}.{$level3Index}.{$level4Index}.{$i}",
                'email' => "level5.user{$level1Index}{$level2Index}{$level3Index}{$level4Index}{$i}@test.com",
                'password' => Hash::make('password'),
                'sponsor_id' => $parentUser->id,
                'wallet_balance' => 20.00,
                'status' => 'active'
            ]);

            // Very few get investments at this level
            if (rand(1, 4) == 1) {
                $this->createInvestment($level5User, $package, rand(5, 8));
            }

            $this->command->info("          ✅ Created {$level5User->name}");
        }
    }

    /**
     * Create investment for user
     */
    private function createInvestment(User $user, InvestmentPackage $package, float $amount)
    {
        Investment::create([
            'user_id' => $user->id,
            'investment_package_id' => $package->id,
            'amount' => $amount,
            'daily_return' => $amount * 0.005, // 0.5% daily return
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'status' => 'active'
        ]);

        $this->command->info("    💰 Added $" . $amount . " investment");
    }

    /**
     * Display the created structure
     */
    private function displayStructure(User $kafy)
    {
        $this->command->info("\n" . str_repeat("=", 80));
        $this->command->info("🌳 CREATED MLM STRUCTURE");
        $this->command->info(str_repeat("=", 80));

        $level1Count = $kafy->referrals()->count();
        $level2Count = 0;
        $level3Count = 0;
        $level4Count = 0;
        $level5Count = 0;

        foreach ($kafy->referrals as $level1) {
            $level2Count += $level1->referrals()->count();
            foreach ($level1->referrals as $level2) {
                $level3Count += $level2->referrals()->count();
                foreach ($level2->referrals as $level3) {
                    $level4Count += $level3->referrals()->count();
                    foreach ($level3->referrals as $level4) {
                        $level5Count += $level4->referrals()->count();
                    }
                }
            }
        }

        $this->command->info("👑 Root: {$kafy->name} (Rank: " . ($kafy->rank ?? 'Guest') . ")");
        $this->command->info("├── Level 1: {$level1Count} users");
        $this->command->info("├── Level 2: {$level2Count} users");
        $this->command->info("├── Level 3: {$level3Count} users");
        $this->command->info("├── Level 4: {$level4Count} users");
        $this->command->info("└── Level 5: {$level5Count} users");

        $totalNetwork = $level1Count + $level2Count + $level3Count + $level4Count + $level5Count;
        $this->command->info("\n🎯 Total Network: {$totalNetwork} users");

        // Count investments
        $totalInvestments = Investment::whereIn('user_id',
            $kafy->referrals()->pluck('id')
                ->concat($kafy->referrals()->with('referrals')->get()->pluck('referrals')->flatten()->pluck('id'))
                ->concat($this->getAllDescendants($kafy)->pluck('id'))
        )->count();

        $this->command->info("💰 Total Investments: {$totalInvestments}");
        $this->command->info(str_repeat("=", 80));
    }

    /**
     * Get all descendants of a user
     */
    private function getAllDescendants(User $user)
    {
        $descendants = collect();

        foreach ($user->referrals as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($this->getAllDescendants($child));
        }

        return $descendants;
    }
}
