<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class blockCompagnie implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Block company if exists in blockCompagnie and current time > blocked_at
        \App\Models\blockCompagnie::query()
            ->where('blocked_at', '<', now())
            ->get()
            ->each(function($block) {
                $company = \App\Models\company::find($block->compagnie_id);
                if ($company) {
                    // Block the company (set is_active to 0 if not already blocked)
                    if ($company->is_block == 0) {
                        $company->is_block = 1;
                        $company->save();
                    }
                }
            });
    }
}
