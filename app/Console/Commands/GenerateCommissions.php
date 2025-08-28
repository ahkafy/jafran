<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Investment;
use App\Models\Commission;
use App\Services\MLMService;

class GenerateCommissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mlm:generate-commissions {--force : Force regenerate commissions even if they exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate commissions for existing investments that dont have commissions';

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
        $force = $this->option('force');

        // Get all investments
        $investments = Investment::with(['user', 'commissions'])->get();

        $processedCount = 0;

        foreach ($investments as $investment) {
            // Check if investment already has commissions
            if (!$force && $investment->commissions->count() > 0) {
                continue;
            }

            // If force is enabled, delete existing commissions
            if ($force) {
                $investment->commissions()->delete();
            }

            // Generate commissions for this investment
            $this->mlmService->calculateCommissions(
                $investment->user,
                $investment,
                $investment->amount
            );

            $processedCount++;
            $this->info("Generated commissions for Investment #{$investment->id} - {$investment->user->name}");
        }

        $this->info("Processed {$processedCount} investments.");

        // Now process the pending commissions
        $commissionCount = $this->mlmService->processCommissions();
        $this->info("Processed {$commissionCount} pending commissions.");

        return 0;
    }
}
