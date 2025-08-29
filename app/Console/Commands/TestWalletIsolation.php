<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\WalletService;
use Illuminate\Console\Command;

class TestWalletIsolation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallet:test-isolation {user : User ID or email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the wallet isolation system with a specific user';

    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        parent::__construct();
        $this->walletService = $walletService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userIdentifier = $this->argument('user');

        // Find user by ID or email
        $user = is_numeric($userIdentifier)
            ? User::find($userIdentifier)
            : User::where('email', $userIdentifier)->first();

        if (!$user) {
            $this->error("User not found: {$userIdentifier}");
            return 1;
        }

        $this->info("Testing wallet isolation for user: {$user->name} ({$user->email})");
        $this->newLine();

        // Show current balances
        $this->showWalletStatus($user);
        $this->newLine();

        // Test scenarios
        if ($this->confirm('Would you like to simulate adding $100 investment funds?')) {
            $this->walletService->addInvestmentFunds($user, 100, 'Test investment funds');
            $this->info('✓ Added $100 to investment balance');
            $this->showWalletStatus($user->fresh());
            $this->newLine();
        }

        if ($this->confirm('Would you like to simulate adding $50 commission?')) {
            $this->walletService->addCommission($user, 50, 'Test commission');
            $this->info('✓ Added $50 to commission balance');
            $this->showWalletStatus($user->fresh());
            $this->newLine();
        }

        if ($this->confirm('Would you like to simulate adding $25 investment returns?')) {
            $this->walletService->addInvestmentReturns($user, 25, 'Test investment returns');
            $this->info('✓ Added $25 to returns balance');
            $this->showWalletStatus($user->fresh());
            $this->newLine();
        }

        // Test withdrawal creation
        if ($this->confirm('Would you like to test withdrawal request creation?')) {
            $user = $user->fresh();
            if ($user->withdrawable_balance > 0) {
                $amount = min(10, $user->withdrawable_balance);

                try {
                    $withdrawal = $this->walletService->createWithdrawalRequest($user, [
                        'amount' => $amount,
                        'balance_type' => 'both',
                        'method' => 'bank',
                        'bank_name' => 'Test Bank',
                        'account_holder_name' => $user->name,
                        'account_number' => '123456789',
                        'routing_number' => '123456789',
                        'bank_country' => 'US',
                        'account_type' => 'checking'
                    ]);

                    $this->info("✓ Created withdrawal request #{$withdrawal->id} for $" . number_format($amount, 2));
                    $this->info("Processing fee: $" . number_format($withdrawal->processing_fee_amount, 2) . " ({$withdrawal->processing_fee_percentage}%)");
                    $this->info("Net amount: $" . number_format($withdrawal->net_amount, 2));
                    $this->info("Processing date: {$withdrawal->scheduled_processing_date->format('M j, Y')}");
                    $this->showWalletStatus($user->fresh());
                } catch (\Exception $e) {
                    $this->error("Failed to create withdrawal: " . $e->getMessage());
                }
            } else {
                $this->warn('No withdrawable balance available');
            }
        }

        // Show next processing date
        $nextProcessing = $this->walletService->getNextProcessingDate();
        $this->info("Next withdrawal processing date: {$nextProcessing['date']->format('M j, Y')} ({$nextProcessing['days_until']} days from now)");

        return 0;
    }

    private function showWalletStatus(User $user)
    {
        $this->table(
            ['Balance Type', 'Amount', 'Description'],
            [
                ['Investment Balance', '$' . number_format($user->investment_balance, 2), 'For investments only (cannot withdraw)'],
                ['Commission Balance', '$' . number_format($user->commission_balance, 2), 'Network earnings (withdrawable)'],
                ['Returns Balance', '$' . number_format($user->return_balance, 2), 'Investment returns (withdrawable)'],
                ['Withdrawable Balance', '$' . number_format($user->withdrawable_balance, 2), 'Total available for withdrawal'],
                ['Pending Withdrawals', '$' . number_format($user->pending_withdrawal_amount, 2), 'Amount reserved for processing'],
            ]
        );
    }
}
