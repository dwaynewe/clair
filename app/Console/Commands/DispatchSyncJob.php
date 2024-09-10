<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Business;
use App\Jobs\PayItemSyncJob;

class DispatchSyncJob extends Command
{
    protected $signature = 'dispatch:sync';
    protected $description = 'Dispatch the PayItemSyncJob for all enabled businesses';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $businesses = Business::where('enabled', true)->get();

        foreach ($businesses as $business) {
            PayItemSyncJob::dispatch($business);
        }

        $this->info('PayItemSyncJob dispatched for all enabled businesses.');
    }
}
