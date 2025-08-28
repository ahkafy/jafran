<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RankingService;
use App\Models\User;

class UpdateUserRanks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-ranks {--user= : Specific user ID to update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user ranks based on their achievements';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rankingService = new RankingService();
        
        $userId = $this->option('user');
        
        if ($userId) {
            // Update specific user
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found.");
                return 1;
            }
            
            $rankChanged = $rankingService->updateUserRank($user);
            
            if ($rankChanged) {
                $this->info("User {$user->name} (ID: {$user->id}) rank updated to: {$user->rank}");
            } else {
                $this->info("User {$user->name} (ID: {$user->id}) rank unchanged: {$user->rank}");
            }
        } else {
            // Update all users
            $this->info('Starting to update ranks for all users...');
            
            $users = User::all();
            $updated = 0;
            $progressBar = $this->output->createProgressBar($users->count());
            
            foreach ($users as $user) {
                if ($rankingService->updateUserRank($user)) {
                    $updated++;
                }
                $progressBar->advance();
            }
            
            $progressBar->finish();
            $this->newLine();
            $this->info("Rank update completed. {$updated} users had their ranks updated.");
        }
        
        return 0;
    }
}
