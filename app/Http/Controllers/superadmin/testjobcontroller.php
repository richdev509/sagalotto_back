<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\testjob;
use Illuminate\Support\Carbon;

class testjobcontroller extends Controller
{
    
    
    
    public function lancement(Request $request)
    {
        echo "lancement";
        $var = 11;
        $job = new testjob($var);
        dispatch($job);

        return response()->json(['message' => 'Job dispatched successfully']);
    }
  
}
