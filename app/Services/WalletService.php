<?php

namespace App\Services;

use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletService
{
    /**
     * Add funds to investment balance (cannot be withdrawn directly)
     */
    public function addInvestmentFunds(User $user, float $amount, string $description = 'Investment funds added')
    {
        return DB::transaction(function () use ($user, $amount, $description) {
            $user->increment('investment_balance', $amount);

            // Log the transaction
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'credit',
                'category' => 'deposit',
                'amount' => $amount,
                'description' => $description,
                'status' => 'completed'
            ]);

            Log::info("Investment funds added", [
                'user_id' => $user->id,
                'amount' => $amount,
                'new_balance' => $user->fresh()->investment_balance
            ]);

            return true;
        });
    }

    /**
     * Add commission earnings (withdrawable)
     */
    public function addCommission(User $user, float $amount, string $description = 'Commission earned')
    {
        return DB::transaction(function () use ($user, $amount, $description) {
            $user->increment('commission_balance', $amount);
            $user->increment('withdrawable_balance', $amount);

            // Log the transaction
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'credit',
                'category' => 'commission',
                'amount' => $amount,
                'description' => $description,
                'status' => 'completed'
            ]);

            Log::info("Commission added", [
                'user_id' => $user->id,
                'amount' => $amount,
                'commission_balance' => $user->fresh()->commission_balance,
                'withdrawable_balance' => $user->fresh()->withdrawable_balance
            ]);

            return true;
        });
    }

    /**
     * Add investment returns (withdrawable)
     */
    public function addInvestmentReturns(User $user, float $amount, string $description = 'Investment returns')
    {
        return DB::transaction(function () use ($user, $amount, $description) {
            $user->increment('return_balance', $amount);
            $user->increment('withdrawable_balance', $amount);

            // Log the transaction
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'credit',
                'category' => 'return',
                'amount' => $amount,
                'description' => $description,
                'status' => 'completed'
            ]);

            Log::info("Investment returns added", [
                'user_id' => $user->id,
                'amount' => $amount,
                'return_balance' => $user->fresh()->return_balance,
                'withdrawable_balance' => $user->fresh()->withdrawable_balance
            ]);

            return true;
        });
    }

    /**
     * Process investment (deduct from investment balance)
     */
    public function processInvestment(User $user, float $amount, string $description = 'Investment made')
    {
        return DB::transaction(function () use ($user, $amount, $description) {
            if ($user->investment_balance < $amount) {
                throw new \Exception('Insufficient investment balance');
            }

            $user->decrement('investment_balance', $amount);
            $user->increment('total_investment', $amount);

            // Log the transaction
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'category' => 'investment',
                'amount' => -$amount,
                'description' => $description,
                'status' => 'completed'
            ]);

            Log::info("Investment processed", [
                'user_id' => $user->id,
                'amount' => $amount,
                'remaining_investment_balance' => $user->fresh()->investment_balance,
                'total_investment' => $user->fresh()->total_investment
            ]);

            return true;
        });
    }

    /**
     * Create withdrawal request (bi-monthly processing)
     */
    public function createWithdrawalRequest(User $user, array $requestData)
    {
        return DB::transaction(function () use ($user, $requestData) {
            $totalAmount = $requestData['amount'];
            $balanceType = $requestData['balance_type'] ?? 'both';
            $method = $requestData['method'];

            // Validate withdrawable balance
            if ($user->withdrawable_balance < $totalAmount) {
                throw new \Exception('Insufficient withdrawable balance');
            }

            // Calculate processing fees
            $bankCountry = $requestData['bank_country'] ?? 'US';
            $feeCalculation = WithdrawalRequest::calculateProcessingFee($totalAmount, $method, $bankCountry);

            // Calculate breakdown
            $breakdown = $this->calculateWithdrawalBreakdown($user, $totalAmount, $balanceType);

            // Determine next processing date
            $processingInfo = $this->getNextProcessingDate();

            // Prepare withdrawal data
            $withdrawalData = [
                'user_id' => $user->id,
                'amount' => $totalAmount,
                'balance_type' => $balanceType,
                'commission_amount' => $breakdown['commission_amount'],
                'returns_amount' => $breakdown['returns_amount'],
                'scheduled_processing_date' => $processingInfo['date'],
                'processing_cycle' => $processingInfo['cycle'],
                'balance_breakdown' => $breakdown,
                'method' => $method,
                'processing_fee_percentage' => $feeCalculation['fee_rate'],
                'processing_fee_amount' => $feeCalculation['fee_amount'],
                'net_amount' => $feeCalculation['net_amount'],
                'status' => WithdrawalRequest::STATUS_PENDING,
                'notes' => $requestData['notes'] ?? null
            ];

            // Add method-specific fields
            if ($method === WithdrawalRequest::METHOD_BANK) {
                $withdrawalData += [
                    'bank_name' => $requestData['bank_name'] ?? null,
                    'account_holder_name' => $requestData['account_holder_name'] ?? null,
                    'account_number' => $requestData['account_number'] ?? null,
                    'routing_number' => $requestData['routing_number'] ?? null,
                    'swift_code' => $requestData['swift_code'] ?? null,
                    'bank_address' => $requestData['bank_address'] ?? null,
                    'bank_city' => $requestData['bank_city'] ?? null,
                    'bank_state' => $requestData['bank_state'] ?? null,
                    'bank_zip_code' => $requestData['bank_zip_code'] ?? null,
                    'bank_country' => $bankCountry,
                    'account_type' => $requestData['account_type'] ?? null,
                ];
            } elseif ($method === WithdrawalRequest::METHOD_MBOOK) {
                $withdrawalData += [
                    'mbook_name' => $requestData['mbook_name'] ?? null,
                    'mbook_country' => $requestData['mbook_country'] ?? null,
                    'mbook_currency' => $requestData['mbook_currency'] ?? null,
                    'mbook_wallet_id' => $requestData['mbook_wallet_id'] ?? null,
                ];
            }

            // Create withdrawal request
            $withdrawal = WithdrawalRequest::create($withdrawalData);

            // Reserve the funds
            $this->reserveWithdrawalFunds($user, $breakdown);

            Log::info("Withdrawal request created", [
                'user_id' => $user->id,
                'withdrawal_id' => $withdrawal->id,
                'amount' => $totalAmount,
                'method' => $method,
                'processing_fee' => $feeCalculation['fee_amount'],
                'net_amount' => $feeCalculation['net_amount'],
                'processing_date' => $processingInfo['date']->format('Y-m-d'),
                'breakdown' => $breakdown
            ]);

            return $withdrawal;
        });
    }

    /**
     * Calculate withdrawal breakdown from available balances
     */
    private function calculateWithdrawalBreakdown(User $user, float $totalAmount, string $balanceType)
    {
        $breakdown = [
            'commission_amount' => 0,
            'returns_amount' => 0,
            'remaining_commission' => $user->commission_balance,
            'remaining_returns' => $user->return_balance,
            'total_available' => $user->withdrawable_balance
        ];

        $remainingAmount = $totalAmount;

        if ($balanceType === 'commission' || $balanceType === 'both') {
            $commissionToUse = min($remainingAmount, $user->commission_balance);
            $breakdown['commission_amount'] = $commissionToUse;
            $remainingAmount -= $commissionToUse;
        }

        if (($balanceType === 'returns' || $balanceType === 'both') && $remainingAmount > 0) {
            $returnsToUse = min($remainingAmount, $user->return_balance);
            $breakdown['returns_amount'] = $returnsToUse;
            $remainingAmount -= $returnsToUse;
        }

        if ($remainingAmount > 0) {
            throw new \Exception('Insufficient balance for withdrawal');
        }

        return $breakdown;
    }

    /**
     * Reserve funds for withdrawal
     */
    private function reserveWithdrawalFunds(User $user, array $breakdown)
    {
        // Deduct from specific balances but not from withdrawable until processed
        if ($breakdown['commission_amount'] > 0) {
            $user->decrement('commission_balance', $breakdown['commission_amount']);
        }

        if ($breakdown['returns_amount'] > 0) {
            $user->decrement('return_balance', $breakdown['returns_amount']);
        }

        // Add to pending withdrawal amount
        $user->increment('pending_withdrawal_amount', $breakdown['commission_amount'] + $breakdown['returns_amount']);
    }

    /**
     * Get next processing date (1st or 16th of month)
     */
    public function getNextProcessingDate()
    {
        $today = Carbon::today();
        $currentDay = $today->day;

        if ($currentDay <= 16) {
            // Next processing is 16th of current month
            $processingDate = $today->copy()->day(16);
            $cycle = 'mid_month';
        } else {
            // Next processing is 1st of next month
            $processingDate = $today->copy()->addMonth()->startOfMonth();
            $cycle = 'month_end';
        }

        return [
            'date' => $processingDate,
            'cycle' => $cycle,
            'days_until' => $today->diffInDays($processingDate)
        ];
    }

    /**
     * Process scheduled withdrawals
     */
    public function processScheduledWithdrawals($processingDate = null)
    {
        $processingDate = $processingDate ?: Carbon::today();

        $pendingWithdrawals = WithdrawalRequest::where('status', WithdrawalRequest::STATUS_PENDING)
            ->where('scheduled_processing_date', '<=', $processingDate)
            ->get();

        $processed = 0;
        $failed = 0;

        foreach ($pendingWithdrawals as $withdrawal) {
            try {
                $this->processWithdrawal($withdrawal);
                $processed++;
            } catch (\Exception $e) {
                Log::error("Failed to process withdrawal", [
                    'withdrawal_id' => $withdrawal->id,
                    'user_id' => $withdrawal->user_id,
                    'error' => $e->getMessage()
                ]);
                $failed++;
            }
        }

        Log::info("Batch withdrawal processing completed", [
            'processing_date' => $processingDate->format('Y-m-d'),
            'processed' => $processed,
            'failed' => $failed
        ]);

        return ['processed' => $processed, 'failed' => $failed];
    }

    /**
     * Process individual withdrawal
     */
    public function processWithdrawal(WithdrawalRequest $withdrawal)
    {
        return DB::transaction(function () use ($withdrawal) {
            $user = $withdrawal->user;

            // Update withdrawal status
            $withdrawal->update([
                'status' => WithdrawalRequest::STATUS_PROCESSED,
                'processed_at' => now(),
                'processed_by' => auth()->id() // If processing manually
            ]);

            // Update user balances
            $user->decrement('withdrawable_balance', $withdrawal->amount);
            $user->decrement('pending_withdrawal_amount', $withdrawal->amount);
            $user->update(['last_withdrawal_processed_at' => now()]);

            // Log the transaction
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'category' => 'withdrawal',
                'amount' => -$withdrawal->amount,
                'description' => "Withdrawal processed - Ref: {$withdrawal->reference_number}",
                'status' => 'completed'
            ]);

            Log::info("Withdrawal processed", [
                'withdrawal_id' => $withdrawal->id,
                'user_id' => $user->id,
                'amount' => $withdrawal->amount,
                'remaining_withdrawable' => $user->fresh()->withdrawable_balance
            ]);

            return true;
        });
    }

    /**
     * Get wallet summary for user
     */
    public function getWalletSummary(User $user)
    {
        return [
            'investment_balance' => $user->investment_balance,
            'commission_balance' => $user->commission_balance,
            'return_balance' => $user->return_balance,
            'withdrawable_balance' => $user->withdrawable_balance,
            'pending_withdrawal_amount' => $user->pending_withdrawal_amount,
            'total_investment' => $user->total_investment,
            'next_processing_date' => $this->getNextProcessingDate(),
            'can_withdraw' => $user->withdrawable_balance > 0,
            'available_for_investment' => $user->investment_balance
        ];
    }
}
