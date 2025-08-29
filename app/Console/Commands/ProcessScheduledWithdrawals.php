<?php

namespace App\Console\Commands;

use App\Services\WalletService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessScheduledWithdrawals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'withdrawals:process {--date= : Processing date (Y-m-d format)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled withdrawals for bi-monthly cycles (1st and 16th)';

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
        $processingDate = $this->option('date')
            ? Carbon::createFromFormat('Y-m-d', $this->option('date'))
            : Carbon::today();

        $this->info("Processing scheduled withdrawals for date: {$processingDate->format('Y-m-d')}");

        // Check if today is a processing day (1st or 16th)
        if (!$this->isProcessingDay($processingDate)) {
            $this->warn("Today is not a processing day. Withdrawals are processed on the 1st and 16th of each month.");
            return;
        }

        try {
            $result = $this->walletService->processScheduledWithdrawals($processingDate);

            $this->info("Withdrawal processing completed:");
            $this->info("- Processed: {$result['processed']} withdrawals");

            if ($result['failed'] > 0) {
                $this->error("- Failed: {$result['failed']} withdrawals");
            } else {
                $this->info("- Failed: {$result['failed']} withdrawals");
            }

        } catch (\Exception $e) {
            $this->error("Error processing withdrawals: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Check if the given date is a processing day
     */
    private function isProcessingDay(Carbon $date)
    {
        return $date->day === 1 || $date->day === 16;
    }
}
