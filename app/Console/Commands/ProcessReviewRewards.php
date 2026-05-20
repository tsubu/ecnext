<?php

namespace App\Console\Commands;

use App\Services\Marketing\ReviewService;
use Illuminate\Console\Command;

class ProcessReviewRewards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marketing:process-rewards';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled rewards for approved product reviews';

    /**
     * Execute the console command.
     */
    public function handle(ReviewService $reviewService)
    {
        $this->info('Starting to process scheduled review rewards...');
        
        $count = $reviewService->processScheduledRewards();
        
        $this->info("Completed. Issued rewards for {$count} reviews.");
        
        return Command::SUCCESS;
    }
}
