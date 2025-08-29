<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\updateLimitPrix;
use App\Jobs\updateDatePlan;
use App\Jobs\truncateLimit;
use App\Jobs\autoActiveTirage;
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->job(new updateLimitPrix())->dailyAt('00:01');
        $schedule->job(new truncateLimit())->dailyAt('00:01');
        $schedule->job(new autoActiveTirage())->dailyAt('00:01');
        $schedule->job(new updateDatePlan)->dailyAt('23:40');



    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

  
}
