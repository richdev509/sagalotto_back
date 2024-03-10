<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TicketVendu;
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
        $amount_tot = 0;
        //tirage

        $ticketId = "";
        // $array = $request->all();
        // $allBolete = $array['bolete'];


        //trouver compagnie
        $comp = company::where([
            ['id', '=', auth()->user()->compagnie_id],
            ['is_delete', '=', 0],
        ])->first();
        if (!$comp) {
            return response()->json([
                'status' => 'false',
                'message' => 'compagnie non trouve',
                'code' => 404,
            ], 404,);
        }
        //verifier si compagnie bloquer
        if ($comp->is_block == '1') {
            return response()->json([
                'status' => 'false',
                'message' => 'compagnie bloque',
                'code' => 404,
            ], 404,);
        }
        //trouver vendeur
        $vendeur = User::where([
            ['id', '=', auth()->user()->id],
            ['is_delete', '=', 0],
        ])->first();
        if (!$vendeur) {
            return response()->json([
                'status' => 'false',
                'message' => 'vendeur non trouve',
                'code' => '404',
            ], 404,);
        }
        //verifier si vendeur bloquer
        if ($vendeur->is_block == '1') {
            return response()->json([
                'status' => 'false',
                'message' => 'vendeur bloque',
                'code' => '404',
            ], 404,);
        }
        $resp = verify::verifierBoulesNonAutorisees($request);
        if ($resp != '1') {
            return $resp;
        }
        $i = 0;
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
            $tirage[] = $tirage_record->name . ', ' . $tirage_record->hour_tirer;
            if (!$tirage_record) {
                return response()->json([
                    'status' => 'false',
                    'message' => 'tirage ferme',
                    'code' => '404',
                ], 404,);
            }
            $montant = verify::calculer_montant($request);
            $amount_tot = $amount_tot + $montant;

            if ($i == '0') {
                $created_at = Carbon::now();
                $ticketId = time() . '-' . rand(1000, 9999);
                $query = DB::table('ticket_code')->insertGetId([
                    'code' => $ticketId,
                    'user_id' => auth()->user()->id,
                    'compagnie_id' => auth()->user()->compagnie_id,
                    'created_at' =>  $created_at,
                ]);
                $boule[] = ['bolete' => $request->input('bolete')];
                $boule[] = ['maryaj' => $request->input('maryaj')];
                $boule[] = ['loto3' => $request->input('loto3')];
                $boule[] = ['loto4' => $request->input('loto4')];
                $boule[] = ['loto5' => $request->input('loto5')];
                $boule[] = ['mariage-gratis' => []];
            }
            $query = DB::table('ticket_vendu')->insertGetId([
                'ticket_code_id' => $ticketId,
                'tirage_record_id' => $tirage_record->id,
                'boule' => json_encode($boule),
                'amount' =>  $montant,
                'created_at' =>  $created_at,
            ]);

            $i++;
        }
        return response()->json([
            'status' => 'true',
            'message' => 'success',
            'code' => '200',
            'head' => [
                'compagnie' => $comp->name,
                'bank' => $vendeur->bank_name,
                '#ticket' => $ticketId,
                'date' => $created_at->format('d-m-y, H:i:s'),
                'tirage' => $tirage
            ],
            'body' => $boule,

            'foot' => [
                'motant' => $amount_tot,
                'info' => $comp->info,
            ]
        ], 200,);
    }
    public function list_ticket(Request $request)
    {

        try {
            $validator = $request->validate([
                "date_debut" => "required",
                "date_fin" => "required",

            ]);
            $vente = DB::table('ticket_code')->where([
                ['ticket_code.compagnie_id', '=', auth()->user()->compagnie_id],
                ['ticket_code.user_id', '=', auth()->user()->id],
                ['ticket_vendu.is_cancel', '=', 0],
                ['ticket_vendu.is_delete', '=', 0]

            ])->whereDate('ticket_code.created_at', '>=', $request->date_debut)
                ->whereDate('ticket_code.created_at', '<=', $request->date_fin)
                ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                ->select('ticket_code.code as ticket_id', 'ticket_code.created_at as date', 'tirage_record.name as tirage', 'ticket_vendu.amount as montant', 'ticket_vendu.winning as gain')
                ->get();
            if ($vente->count() > 0) {
                return response()->json([
                    'status' => 'true',
                    "code" => '200',
                    "ticket" => $vente

                ], 200,);
            } else {
                return response()->json([
                    'status' => 'false',
                    "code" => '404',
                    "message" => 'pas de ticket'

                ], 404,);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                "message" => $th->getMessage(),

            ], 500,);
        }
    }

    public function cancel_ticket(Request $request)
    {


        try {

            $validator = $request->validate([
                "id" => "required",

            ]);

            $delai = DB::table('ticket_suppression')->where([
                ['compagnie_id', '=', auth()->user()->compagnie_id],
                ['is_active', '=', 1]
            ])->first();
            if (!$delai) {
                return response()->json([
                    'status' => 'false',
                    "code" => '404',
                    "message" => 'vous pouvez pas annuler'

                ], 404,);
            }


            $ticket = DB::table('ticket_code')->where([
                ['compagnie_id', '=', auth()->user()->compagnie_id],
                ['user_id', '=', auth()->user()->id],

                ['code', '=', $request->input('id')]

            ])->whereDate('created_at', Carbon::now())
                ->first();
            //ticket not exist
            if (!$ticket) {
                return response()->json([
                    'status' => 'false',
                    "code" => '404',
                    "message" => 'ticket id pas trouver'
                ], 404,);
            }

            //get tirage for compare date of end
            $tirage_ids = DB::table('ticket_vendu')->where([
                ['ticket_code_id', '=', $request->input('id')],
                ['is_cancel', '=', 0],
                ['is_delete', '=', 0],

            ])->select('tirage_record_id as tirage_id')
                ->get();
            if ($tirage_ids->count() <= 0) {
                return response()->json([
                    'status' => 'false',
                    "code" => '404',
                    "message" => 'ticket deja annuler'

                ], 404,);
            }
            //chek if tirage end or active
            foreach ($tirage_ids as $id) {
                $tirage_record = tirage_record::where([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    //  ['is_active', '=', '1'],
                    ['id', '=', $id->tirage_id]
                ])->whereTime(
                    'hour',
                    '>',
                    Carbon::now()->format('H:i:s'),
                )->first();
                if (!$tirage_record) {
                    return response()->json([
                        'status' => 'false',
                        "code" => '404',
                        "message" => 'tirage inactive'

                    ], 404,);
                }
                //delai from directeur input
                if ($delai->delai < 10) {
                    $delai_conversion = "00: 0" . $delai->delai . ":00";
                } else {
                    $delai_conversion = "00:" . $delai->delai . ":00";
                }
                //the date of the ticket creation
                $datet =  Carbon::parse($ticket->created_at);
                $now = Carbon::now();
                $now = Carbon::parse($now);
                $dif = $datet->diffInMinutes($now);

                if ($dif >= $delai->delai) {
                    return response()->json([
                        'status' => 'false',
                        "code" => '404',
                        "message" => 'dalai ecouler'

                    ], 404,);
                }

                if ($datet->minute < 10) {
                    $mminute = "0" . $datet->minute;
                } else {
                    $mminute = $datet->minute;
                }
                if ($datet->hour < 10) {
                    $hhour = "0" . $datet->hour;
                } else {
                    $hhour = $datet->hour;
                }
                if ($datet->second < 10) {
                    $ssecond = "0" . $datet->second;
                } else {
                    $ssecond = $datet->second;
                }

                $hour_creation = $hhour . ":" . $mminute . ":" . $ssecond;
                // dd($hour_creation);

                // Parse the times into Carbon instances with a default date
                $carbonTime1 = Carbon::createFromFormat('H:i:s', $hour_creation);
                $carbonTime2 = Carbon::createFromFormat('H:i:s', $delai_conversion);
                // Add the second time to the first time
                $summedTime = $carbonTime1->addHours($carbonTime2->hour)->addMinutes($carbonTime2->minute)->addSeconds($carbonTime2->second);
                // Format the summed time as desired (optional)
                $timesum = $summedTime->format('H:i:s');

                if ($timesum > $tirage_record->hour) {
                    return response()->json([
                        'status' => 'false',
                        "code" => '404',
                        "message" => 'tirage deja ferme'

                    ], 404,);
                }
            }

            $ticket_vendu = DB::table('ticket_vendu')->where([
                ['ticket_code_id', '=', $request->input('id')]
            ])->update([
                'is_cancel' => 1
            ]);
            return response()->json([
                'status' => 'true',
                "code" => '200',
                "message" => 'ticket annule'

            ], 200,);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                "message" => $th->getMessage(),

            ], 500,);
        }
    }
    public function report_ticket(Request $request)
    {
        try {

            $validator = $request->validate([
                "date_debut" => "required",
                "date_fin" => "required",
                "tirage" => "required",

            ]);
            //find for all tirage
            if ($request->input('tirage') == "tout") {

                $vente = DB::table('ticket_code')->where([
                    ['ticket_code.compagnie_id', '=', auth()->user()->compagnie_id],
                    ['ticket_code.user_id', '=', auth()->user()->id],
                    ['ticket_vendu.is_cancel', '=', 0],
                    ['ticket_vendu.is_delete', '=', 0],

                ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                    ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                    ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                    ->sum('amount');

                $perte = DB::table('ticket_code')->where([
                    ['ticket_code.compagnie_id', '=', auth()->user()->compagnie_id],
                    ['ticket_code.user_id', '=', auth()->user()->id],
                    ['ticket_vendu.is_cancel', '=', 0],
                    ['ticket_vendu.is_delete', '=', 0],

                ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                    ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                    ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                    ->sum('winning');


                $ticket_win = DB::table('ticket_code')->where([
                    ['ticket_code.compagnie_id', '=', auth()->user()->compagnie_id],
                    ['ticket_code.user_id', '=', auth()->user()->id],
                    ['ticket_vendu.is_cancel', '=', 0],
                    ['ticket_vendu.is_delete', '=', 0],
                    ['ticket_vendu.is_win', '=', 1],


                ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                    ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                    ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                    ->get()->count();

                $ticket_lose = DB::table('ticket_code')->where([
                    ['ticket_code.compagnie_id', '=', auth()->user()->compagnie_id],
                    ['ticket_code.user_id', '=', auth()->user()->id],
                    ['ticket_vendu.is_cancel', '=', 0],
                    ['ticket_vendu.is_delete', '=', 0],
                    ['ticket_vendu.is_win', '=', 0],


                ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                    ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                    ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                    ->get()->count();
               
               
                    return response()->json([
                    'status' => 'true',
                    "code" => '200',
                    "date"=>Carbon::now(),
                    'tirage'=>$request->input('tirage'),
                    "rapport" => $request->input('date_debut') . " au " . $request->input('date_fin'),
                    "ticket_gain"=>$ticket_win,
                    "ticket_perte"=>$ticket_lose,
                    "ticket_total"=>$ticket_win + $ticket_lose,
                    "vente"=>$vente,
                    "perte"=>$perte,
                    "balance"=>$vente - $perte


                ], 200,);
            }else{
                
                $tirage_record = tirage_record::where([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    ['name', '=', $request->input('tirage')]
                ])->first();
                if(!$tirage_record){
                    return response()->json([
                        "status" => 'false',
                        "code" => '404',
                        "message"=>"tirage non trouve"
                    ], 404,);

                }
                $vente = DB::table('ticket_code')->where([
                    ['ticket_code.compagnie_id', '=', auth()->user()->compagnie_id],
                    ['ticket_code.user_id', '=', auth()->user()->id],
                    ['ticket_vendu.is_cancel', '=', 0],
                    ['ticket_vendu.is_delete', '=', 0],
                    ['ticket_vendu.tirage_record_id', '=', $tirage_record->id],


                ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                    ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                    ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                    ->sum('amount');

                $perte = DB::table('ticket_code')->where([
                    ['ticket_code.compagnie_id', '=', auth()->user()->compagnie_id],
                    ['ticket_code.user_id', '=', auth()->user()->id],
                    ['ticket_vendu.is_cancel', '=', 0],
                    ['ticket_vendu.is_delete', '=', 0],
                    ['ticket_vendu.tirage_record_id', '=', $tirage_record->id],

                ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                    ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                    ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                    ->sum('winning');


                $ticket_win = DB::table('ticket_code')->where([
                    ['ticket_code.compagnie_id', '=', auth()->user()->compagnie_id],
                    ['ticket_code.user_id', '=', auth()->user()->id],
                    ['ticket_vendu.is_cancel', '=', 0],
                    ['ticket_vendu.is_delete', '=', 0],
                    ['ticket_vendu.is_win', '=', 1],
                    ['ticket_vendu.tirage_record_id', '=', $tirage_record->id],


                ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                    ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                    ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                    ->get()->count();

                $ticket_lose = DB::table('ticket_code')->where([
                    ['ticket_code.compagnie_id', '=', auth()->user()->compagnie_id],
                    ['ticket_code.user_id', '=', auth()->user()->id],
                    ['ticket_vendu.is_cancel', '=', 0],
                    ['ticket_vendu.is_delete', '=', 0],
                    ['ticket_vendu.is_win', '=', 0],
                    ['ticket_vendu.tirage_record_id', '=', $tirage_record->id],


                ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                    ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                    ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                    ->get()->count();
               
               
                    return response()->json([
                    'status' => 'true',
                    "code" => '200',
                    "date"=>Carbon::now(),
                    'tirage'=>$request->input('tirage'),
                    "rapport" => $request->input('date_debut') . " au " . $request->input('date_fin'),
                    "ticket_gain"=>$ticket_win,
                    "ticket_perte"=>$ticket_lose,
                    "ticket_total"=>$ticket_win + $ticket_lose,
                    "vente"=>$vente,
                    "perte"=>$perte,
                    "balance"=>$vente - $perte


                ], 200,);








            }



           

           
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                "message" => $th->getMessage(),

            ], 500,);
        }
    }
}
