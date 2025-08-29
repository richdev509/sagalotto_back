<?php

namespace App\Jobs;

use App\Models\company;
use App\Models\ticket_code;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class updateDatePlan implements ShouldQueue
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
        $companies = Company::whereNotNull('dateexpiration')->get();
        $today = Carbon::now()->format('Y-m-d');

        foreach ($companies as $company) {
            $ticketCount = Ticket_Code::where('compagnie_id', $company->id)
                ->whereDate('created_at', $today)
                ->count();

            if ($ticketCount < 5) {
                $company->dateexpiration = Carbon::parse($company->dateexpiration)
                    ->addDay()
                    ->format('Y-m-d');
                $company->save();
            }
        }
    }
}
