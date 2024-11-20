<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\testjob;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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
    public function jobUpdateLimit()
    {
        DB::table('limit_prix_boul')->update([
            'montant' => DB::raw('montant1')
        ]);

        // Log to confirm the handle function was called
        dd('CopyMontant1ToMontant2 job executed.');
    }

    public function jobAutoActive(){
        if (Carbon::now()->isSunday()) {
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
        if(Carbon::now()->isMonday()) {
            DB::table('tirage_record')
            ->where([
                ['is_active', '=', 0],
                ['autoActive', '=', 1],
                ['tirage_id','>=', 7],
                ['tirage_id','<=', 12]

            ])->update([
                'is_active' => 1,
                'autoActive'=> ''

            ]);

        }
        dd('job run ');
    }
  
}
