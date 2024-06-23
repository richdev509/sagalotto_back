<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\monitor;
use Illuminate\Support\Facades\Log;

class testjob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $test;

    public function __construct($var)
    {
        $this->test = $var;
    }

    public function handle()
{
    try {
        $query=monitor::create([
            'userid'=>$this->test,
            'compagnieid'=>$this->test,
            'totalfiche'=>$this->test,
            'tirage_id'=>$this->test,
        ]);

       
    } catch (\Exception $e) {
       

    }
}

}
