<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MLMService;
use Carbon\Carbon;

class ProcessDailyReturns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mlm:process-returns {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process daily returns for investments';

    protected $mlmService;

    /**
     * Create a new command instance.
     */
    public function __construct(MLMService $mlmService)
    {
        parent::__construct();
        $this->mlmService = $mlmService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->argument('date') ?: Carbon::today()->format('Y-m-d');

        $this->info("Processing daily returns for: {$date}");

        $processedCount = $this->mlmService->processDailyReturns($date);

        $this->info("Processed {$processedCount} daily returns.");

        // Also process pending commissions
        $commissionCount = $this->mlmService->processCommissions();

        $this->info("Processed {$commissionCount} commissions.");

        return 0;
    }
}
