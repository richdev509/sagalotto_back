<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\company;
use App\Models\User;
use App\Http\Controllers\api\verificationController as verify;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\tirage_record;
class ticketController extends Controller
{




    public function creer_ticket(Request $request)
    {  
         // prix total
        $amount_tot=0;
        //tirage
       
        $ticketId ="";
       // $array = $request->all();
       // $allBolete = $array['bolete'];
        

     //trouver compagnie
        $comp = company::where([
            ['id', '=', auth()->user()->compagnie_id],
            ['is_delete', '=', 0],
        ])->first();
        if(!$comp){
            return response()->json([
                'status' =>'false',
                'message' =>'compagnie non trouve',
                'code' => 404,
            ],404,);

        }
        //verifier si compagnie bloquer
        if($comp->is_block =='1'){
            return response()->json([
                'status' =>'false',
                'message' =>'compagnie bloque',
                'code' => 404,
            ],404,);
        }
        //trouver vendeur
        $vendeur = User::where([
            ['id', '=', auth()->user()->id],
            ['is_delete', '=', 0],
        ])->first();
        if(!$vendeur){
            return response()->json([
                'status' =>'false',
                'message' =>'vendeur non trouve',
                'code' => '404',
            ],404,);

        }
        //verifier si vendeur bloquer
        if($vendeur->is_block =='1'){
            return response()->json([
                'status' =>'false',
                'message' =>'vendeur bloque',
                'code' => '404',
            ],404,);
        }
        $resp = verify::verifierBoulesNonAutorisees($request);
        if ($resp != '1') {
            return $resp;
        }
        $i =0;
        //$ticketId[];
        foreach ($request->input('tirages') as $name) {
           
  
            $tirage_record = tirage_record::where([
                ['compagnie_id', '=', auth()->user()->compagnie_id],
                ['is_active', '=', '1'],
                ['name', '=', $name['name']]
            ])->whereTime(
                'hour',
                '>',
                Carbon::now()->format('H:i:s'),
            )->first();
            $tirage[] = $tirage_record->name.', '.$tirage_record->hour_tirer;
            if(!$tirage_record){
                return response()->json([
                    'status' =>'false',
                    'message' =>'tirage ferme',
                    'code' => '404',
                ],404,);

            }
            $montant = verify::calculer_montant($request);
            $amount_tot = $amount_tot + $montant;
            
            if($i=='0'){
                $created_at = Carbon::now();
                $ticketId = time() . '-' . rand(1000, 9999);
                $query = DB::table('ticket_code')->insertGetId([
                    'code' => $ticketId,
                    'user_id' => auth()->user()->id,
                    'compagnie_id' => auth()->user()->compagnie_id,
                    'created_at' =>  $created_at,     
                ]);
                $boule [] = ['bolete' => $request->input('bolete')];
                $boule [] = ['maryaj' => $request->input('maryaj')];
                $boule [] = ['loto3' => $request->input('loto3')];
                $boule [] = ['loto4' => $request->input('loto4')];
                $boule [] = ['loto5' => $request->input('loto5')];
                $boule [] = ['mariage-gratis' => []];

            }
            $query = DB::table('ticket_vendu')->insertGetId([
                'ticket_code_id' => $ticketId,
                'tirage_record_id' => $tirage_record->id,
                'boule' =>json_encode($boule),
                'amount' =>  $montant, 
                'created_at' =>  $created_at,     
            ]);
           
            $i++;

           
           
       
        }
        return response()->json([
            'status' =>'true',
            'message' =>'success',
            'code' => '200',
            'head'=>[
                'compagnie'=>$comp->name,
                'bank'=> $vendeur->bank_name,
                '#ticket'=>$ticketId,
                'date'=>$created_at->format('d-m-y, H:i:s'),
                'tirage'=> $tirage
            ],
            'body'=> $boule,

            'foot'=>[
                'motant'=> $amount_tot,
                'info'=> $comp->info,
            ]
        ],200,);
        
      
      

    }
}
