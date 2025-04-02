<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class autoActiveTirage implements ShouldQueue
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
        if (Carbon::today()->isSunday()) {
            DB::table('tirage_record')
                ->where('is_active', '=', '1')
                ->where([
                    ['tirage_id','>=', 7],
                    ['tirage_id','<=', 12]

                ])
                ->update([
                    'is_active' => '0',
                    'autoActive' =>'1'

                ]);
        }
        if(Carbon::today()->isMonday()) {
            DB::table('tirage_record')
            ->where([
                ['is_active', '=', '0'],
                ['autoActive', '=', '1'],
                ['tirage_id','>=', 7],
                ['tirage_id','<=', 12]

            ])->update([
                'is_active' => '1',
                'autoActive'=> '0'

            ]);

        }
    }
  
    
}
