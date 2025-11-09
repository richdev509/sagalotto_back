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
        $compagnies2 = Company::all();
        foreach ($compagnies2 as $compagnie2) {
            // Robust check: count any tickets created in the last 10 days (inclusive)
            $start = Carbon::today()->subDays(9)->startOfDay();
            $end = Carbon::today()->endOfDay();
            $ticketCount2 = Ticket_Code::where('compagnie_id', $compagnie2->id)
                ->whereBetween('created_at', [$start, $end])
                ->count();

            if ($ticketCount2 === 0) {
                // mark as deleted/disabled only if currently not already marked
                if ($compagnie2->is_delete != 1) {
                    $compagnie2->is_delete = 1;
                    $compagnie2->is_active = 0;
                    $compagnie2->save();
                }
            } else {
                // restore if previously marked deleted but activity resumed
                if ($compagnie2->is_delete == 1) {
                    $compagnie2->is_delete = 0;
                    $compagnie2->is_active = 1;
                    $compagnie2->save();
                }
            }

        }

    }
}
