<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TicketVendu;
use Illuminate\Http\Request;
use App\Models\company;
use App\Models\User;
use App\Http\Controllers\api\verificationController as verify;
use App\Models\maryajgratis;
use App\Models\seting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\tirage_record;
use App\Models\ticket_code;

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
        //all mariage gratuit
        //trouver compagnie
        //filter data to remove zero value before process
        $filter_l4  = verify::removeZeroOptions($request);

        $comp = company::where([
            ['id', '=', auth()->user()->compagnie_id],
            ['is_delete', '=', 0],
        ])->first();
        if (!$comp) {
            return response()->json([
                'status' => 'false',
                'message' => [
                    'info' => 'Gen yon problem',
                    'boule' => 'Konpayi sa pa trouve',


                ],
                'code' => '404'

            ], 404,);
        }
        //verifier si compagnie bloquer
        if ($comp->is_block == '1') {
            return response()->json([
                'status' => 'false',
                'message' => [
                    'info' => 'Konpayi a bloke',
                    'boule' => 'Kontakte met bolet la',


                ],
                'code' => '404'

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
                'message' => [
                    'info' => 'Gen yon problem',
                    'boule' => 'Machin ou an bloke',


                ],
                'code' => '404'

            ], 404,);
        }
        //check if mariage gratuit is active
        $mg = maryajgratis::where([
            ['compagnie_id', '=', auth()->user()->compagnie_id],
            ['etat', '=', '1'],
            ['branch_id', '=', auth()->user()->branch_id]
        ])->first();
        //check setting et call method
        $setting = seting::where([
            ['compagnie_id', '=', auth()->user()->compagnie_id],
        ])->first();
        if ($setting) {
            $sett = verify::limiteNumberPlay($request, $setting);
            if ($sett != '1') {
                return $sett;
            }
        }
        //verify number that are limited in price

        //tchek if all tirage are open before proceed
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
            if (!$tirage_record) {
                return response()->json([
                    'status' => 'false',
                    'message' => $name['name'] . ' ferme',
                    'code' => '404',
                ], 404,);
            }
        }
        //verify number that are blocked
        $resp = verify::verifierBoulesNonAutorisees($request);
        if ($resp != '1') {
            return $resp;
        }
        $i = 0;

        //$ticketId[];
        //tchek if all tirage are open before proceed
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
            if (!$tirage_record) {
                return response()->json([
                    'status' => 'false',
                    'message' => $name['name'] . ' ferme',
                    'code' => '404',
                ], 404,);
            }

            //verify limit boule for each tirage before
            $resp_boul = verify::verifierLimitePrixBoule($request, $name['name']);
            if ($resp_boul != '1') {
                return $resp_boul;
            }

            //verify number that are limited in price
            $resp_prix = verify::verifierLimitePrixJouer($request, $name['name']);
            if ($resp_prix != '1') {
                return $resp_prix;
            }
        }
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
            if (!$tirage_record) {
                return response()->json([
                    'status' => 'false',
                    'message' => 'tirage ferme',
                    'code' => '404',
                ], 404,);
            }
            $tirage[] = $tirage_record->name . ', ' . $tirage_record->hour_tirer;
            //calcul amout total
            $montant = verify::calculer_montant($request);
            $amount_tot = $amount_tot + $montant;
            //store each boul

            if ($i == '0') {
                $created_at = Carbon::now();
                $ticketId = time() . '-' . rand(1000, 9999);
                $query = DB::table('ticket_code')->insertGetId([
                    'code' => $ticketId,
                    'user_id' => auth()->user()->id,
                    'compagnie_id' => auth()->user()->compagnie_id,
                    'branch_id' => $vendeur->branch_id,
                    'created_at' =>  $created_at,
                ]);
                $boule[] = ['bolete' => $request->input('bolete')];
                $boule[] = ['maryaj' => $request->input('maryaj')];
                $boule[] = ['loto3' => $request->input('loto3')];
                $boule[] = ['loto4' => $filter_l4['loto4']];
                $boule[] = ['loto5' => $request->input('loto5')];
                // $boule[] = ['mariage-gratis' =>[]];
            }
            //call mariage gratuit if is active
            if ($mg) {
                $mg_res = verify::generer_gratuit($mg, $montant, $name['name']);

                if ($mg_res != false) {
                    $maryaj_all[] =  array_merge($mg_res);
                    $mergedResults = [];

                    foreach ($maryaj_all as $drawResults) {
                        // Add each draw's results to the merged list
                        $mergedResults = array_merge($mergedResults, $drawResults);
                    }
                    if (array_key_exists(5, $boule)) {
                        array_splice($boule, 5, 1);
                    }
                    $boule[] = ['mariage_gratis' => $mg_res];
                }
                unset($mg_res);
            }
            $query = DB::table('ticket_vendu')->insertGetId([
                'ticket_code_id' => $ticketId,
                'tirage_record_id' => $tirage_record->id,
                'boule' => json_encode($boule),
                'amount' =>  $montant,
                'commission' => ($montant * $vendeur->percent) / 100,

                'created_at' =>  $created_at,
            ]);

            $i++;
        }
        if (!empty($maryaj_all)) {
            if (array_key_exists(5, $boule)) {
                array_splice($boule, 5, 1);
            }


            $boule[] = ['mariage-gratis' => $mergedResults];
        } else {
            $boule[] = ['mariage-gratis' => []];
        }
        verify::StockerLimitePrixJouer($request);

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
    public function creer_ticket2(Request $request)
    {

        // prix total
        $amount_tot = 0;
        //tirage

        $ticketId = "";
        // $array = $request->all();
        // $allBolete = $array['bolete'];
        //all mariage gratuit
        //trouver compagnie
        //filter data to remove zero value before process
        $filter_l4  = verify::removeZeroOptions($request);

        $comp = company::where([
            ['id', '=', auth()->user()->compagnie_id],
            ['is_delete', '=', 0],
        ])->first();
        if (!$comp) {
            return response()->json([
                'status' => 'false',
                'message' => [
                    'info' => 'Gen yon problem',
                    'boule' => 'Konpayi sa pa trouve',


                ],
                'code' => '404'

            ], 404,);
        }
        //verifier si compagnie bloquer
        if ($comp->is_block == '1') {
            return response()->json([
                'status' => 'false',
                'message' => [
                    'info' => 'Konpayi a bloke',
                    'boule' => 'Kontakte met bolet la',


                ],
                'code' => '404'

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
                'message' => [
                    'info' => 'Gen yon problem',
                    'boule' => 'Machin ou an bloke',


                ],
                'code' => '404'

            ], 404,);
        }
        //check if mariage gratuit is active
        $mg = maryajgratis::where([
            ['compagnie_id', '=', auth()->user()->compagnie_id],
            ['etat', '=', '1'],
            ['branch_id', '=', auth()->user()->branch_id]
        ])->first();
        //check setting et call method
        $setting = seting::where([
            ['compagnie_id', '=', auth()->user()->compagnie_id],
        ])->first();
        if ($setting) {
            $sett = verify::limiteNumberPlay($request, $setting);
            if ($sett != '1') {
                return $sett;
            }
        }
        //tchek if all tirage are open before proceed
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
            if (!$tirage_record) {
                return response()->json([
                    'status' => 'false',
                    'message' => $name['name'] . ' ferme',
                    'code' => '404',
                ], 404,);
            }
        }
        //verify number that are blocked
        $resp = verify::verifierBoulesNonAutorisees($request);
        if ($resp != '1') {
            return $resp;
        }
        $i = 0;

        //$ticketId[];
        //tchek if all tirage are open before proceed
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
            if (!$tirage_record) {
                return response()->json([
                    'status' => 'false',
                    'message' => $name['name'] . ' ferme',
                    'code' => '404',
                ], 404,);
            }

            //verify limit boule for each tirage before
            $resp_boul = verify::verifierLimitePrixBoule($request, $name['name']);
            if ($resp_boul != '1') {
                return $resp_boul;
            }

            //verify number that are limited in price
            $resp_prix = verify::verifierLimitePrixJouer($request, $name['name']);
            if ($resp_prix != '1') {
                return $resp_prix;
            }
        }
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
            if (!$tirage_record) {
                return response()->json([
                    'status' => 'false',
                    'message' => 'tirage ferme',
                    'code' => '404',
                ], 404,);
            }
            $tirage[] = $tirage_record->name . ', ' . $tirage_record->hour_tirer;
            //calcul amout total
            $montant = verify::calculer_montant($request);
            $amount_tot = $amount_tot + $montant;
            //store each boul

            if ($i == '0') {
                $created_at = Carbon::now();
                $ticketId = time() . '-' . rand(1000, 9999);
                $query = DB::table('ticket_code')->insertGetId([
                    'code' => $ticketId,
                    'user_id' => auth()->user()->id,
                    'compagnie_id' => auth()->user()->compagnie_id,
                    'branch_id' => $vendeur->branch_id,
                    'created_at' =>  $created_at,
                ]);
                $boule[] = ['bolete' => $request->input('bolete')];
                $boule[] = ['maryaj' => $request->input('maryaj')];
                $boule[] = ['loto3' => $request->input('loto3')];
                $boule[] = ['loto4' => $filter_l4['loto4']];
                $boule[] = ['loto5' => $request->input('loto5')];
                // $boule[] = ['mariage-gratis' =>[]];
            }
            //call mariage gratuit if is active
            if ($mg) {
                $mg_res = verify::generer_gratuit($mg, $montant, $name['name']);

                if ($mg_res != false) {
                    $maryaj_all[] =  array_merge($mg_res);
                    $mergedResults = [];

                    foreach ($maryaj_all as $drawResults) {
                        // Add each draw's results to the merged list
                        $mergedResults = array_merge($mergedResults, $drawResults);
                    }
                    if (array_key_exists(5, $boule)) {
                        array_splice($boule, 5, 1);
                    }
                    $boule[] = ['mariage_gratis' => $mg_res];
                }
                unset($mg_res);
            }
            $query = DB::table('ticket_vendu')->insertGetId([
                'ticket_code_id' => $ticketId,
                'tirage_record_id' => $tirage_record->id,
                'boule' => json_encode($boule),
                'amount' =>  $montant,
                'commission' => ($montant * $vendeur->percent) / 100,
                'pending' => 1,
                'created_at' =>  $created_at,
            ]);

            $i++;
        }
        if (!empty($maryaj_all)) {
            if (array_key_exists(5, $boule)) {
                array_splice($boule, 5, 1);
            }


            $boule[] = ['mariage-gratis' => $mergedResults];
        } else {
            $boule[] = ['mariage-gratis' => []];
        }
        verify::StockerLimitePrixJouer($request);

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
    public function confirm_ticket(Request $request)
    {
        $validator = $request->validate([
            "id" => "required",
        ]);
        $ticket = DB::table('ticket_vendu')->where([
            ['ticket_code_id', '=', $request->input('id')]
        ])->first();
        if ($ticket) {
            $ticket_vendu = DB::table('ticket_vendu')->where([
                ['ticket_code_id', '=', $request->input('id')]
            ])->update([
                'pending' => 0
            ]);

            return response()->json([
                'status' => 'true',
                "code" => '200',
                "message" => 'ticket confirmer'

            ], 200,);
        } else {
            return response()->json([
                'status' => 'false',
                "code" => '404',
                "message" => 'ticket pas trouver'

            ], 200,);
        }
    }

    public function list_ticket(Request $request)
    {

        try {
            $validator = $request->validate([
                "date_debut" => "required",
                "date_fin" => "required",

            ]);
            $vente = DB::table('ticket_code')
                ->where([
                    ['ticket_code.compagnie_id', '=', auth()->user()->compagnie_id],
                    ['ticket_code.user_id', '=', auth()->user()->id],
                    ['ticket_vendu.is_cancel', '=', 0],
                    ['ticket_vendu.is_delete', '=', 0],
                    ['ticket_vendu.pending', '=', 0]
                ])
                ->whereDate('ticket_code.created_at', '>=', $request->date_debut)
                ->whereDate('ticket_code.created_at', '<=', $request->date_fin)
                ->select(
                    'ticket_code.code as ticket_id',
                    'ticket_code.created_at as date',
                    DB::raw('(SELECT name FROM tirage_record WHERE tirage_record.id = ticket_vendu.tirage_record_id LIMIT 1) as tirage'),
                    'ticket_vendu.amount as montant',
                    'ticket_vendu.winning as gain',
                    'ticket_vendu.is_payed as payer'
                )
                ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code') // join is still used for ticket_vendu
                ->orderBy('ticket_code.id', 'desc')
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
                "check" => "required"
            ]);
            if ($request->input('check') == '1') {
                $ticket = DB::table('ticket_code')->where([
                    ['ticket_code.compagnie_id', '=', auth()->user()->compagnie_id],
                    ['ticket_code.user_id', '=', auth()->user()->id],
                    ['ticket_vendu.is_cancel', '=', 0],
                    ['ticket_vendu.is_delete', '=', 0],
                    ['ticket_vendu.ticket_code_id', '=', $request->input('id')],



                ])
                    ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                    ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                    ->select('ticket_code.code as ticket_id', 'ticket_code.created_at as date', 'tirage_record.name as tirage', 'ticket_vendu.amount as montant', 'ticket_vendu.winning as gain')
                    ->get();
                if ($ticket->count() > 0) {
                    $i = 0;
                    foreach ($ticket as $row) {
                        if ($i == 0) {
                            $date = $row->date;
                            $ticket_id = $row->ticket_id;
                            $montant = $row->montant;
                        }
                    }
                    return response()->json([
                        'status' => 'true',
                        "code" => '404',
                        "ticket" => [
                            'ticket_id' => $ticket_id,
                            'date' => $date,
                            'montant' => $montant * $ticket->count()
                        ]


                    ], 200,);
                } else {
                    return response()->json([
                        'status' => 'false',
                        "code" => '404',
                        "message" => 'ticket pas trouver'

                    ], 404,);
                }
            } else {

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

                if ($tirage_ids->count() == 0) {
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
                        $delai_conversion = "00:0" . $delai->delai . ":00";
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
                    $difff = $datet->diff($now);
                    // Parse the times into Carbon instances with a default date
                    $carbonTime1 = Carbon::createFromFormat('H:i:s', $hour_creation);
                    $carbonTime1->addHours($difff->h)
                        ->addMinutes($difff->i)
                        ->addSeconds($difff->s);
                    // $carbonTime2 = Carbon::createFromFormat('H:i:s', $delai_conversion);
                    // Add the second time to the first time

                    // $summedTime = $carbonTime1->addHours($carbonTime2->hour)->addMinutes($carbonTime2->minute)->addSeconds($carbonTime2->second);
                    // Format the summed time as desired (optional)
                    // $timesum = $summedTime->format('H:i:s');
                    //dd($timesum, $tirage_record->hour);
                    //dd($carbonTime1, $tirage_record->hour);
                    $recordHour = Carbon::createFromFormat('H:i:s',  $tirage_record->hour);
                    if ($carbonTime1 >  $recordHour) {
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
            }
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

            ]);
            //find for all tirage
            $comp = DB::table('companies')->where('id', '=', auth()->user()->compagnie_id)->first();
            if ($comp->is_block == 1) {
                return response()->json([
                    'status' => 'true',
                    "code" => '200',
                    "date" => 'Compagnie bloke',
                    'tirage' => 'Compagnie bloke',
                    "rapport" => 'Compagnie bloke',
                    "ticket_gain" => 'Compagnie bloke',
                    "ticket_perte" => 'Compagnie bloke',
                    "ticket_total" => 'Compagnie bloke',
                    "vente" => 'Compagnie bloke',
                    "perte" => 'Compagnie bloke',
                    'commission' => 'Compagnie bloke',
                    "balance" => 'Compagnie bloke',


                ], 200,);
            }
            if ($request->input('tirage') == "Tout" || $request->input('tirage') == "") {


                $startDate = $request->input('date_debut') . ' 00:00:00';
                $endDate = $request->input('date_fin') . ' 23:59:59';
                $ticketCodes = ticket_Code::where('compagnie_id', auth()->user()->compagnie_id)
                    ->where('user_id', auth()->user()->id)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->pluck('code');
                //i use a batch for divide the query to void long time process querie
                $batchSize = 500;  // Define a reasonable chunk size for the batch
                $baseQuery = collect();

                foreach (array_chunk($ticketCodes->toArray(), $batchSize) as $chunk) {
                    $chunkResults = DB::table('ticket_vendu')
                        ->whereIn('ticket_code_id', $chunk)
                        ->where([
                            ['is_cancel', '=', 0],
                            ['is_delete', '=', 0],
                            ['pending', '=', 0],
                        ])
                        ->get();

                    $baseQuery = $baseQuery->merge($chunkResults);
                }

                // Calculate sums
                $vente = (clone $baseQuery)->sum('amount');
                $perte = (clone $baseQuery)->sum('winning');
                $commission = (clone $baseQuery)->sum('commission');

                // Calculate win and lose ticket counts
                $ticket_win = (clone $baseQuery)->where('is_win', 1)->count();
                $ticket_lose = (clone $baseQuery)->where('is_win', 0)->count();



                return response()->json([
                    'status' => 'true',
                    "code" => '200',
                    "date" => Carbon::now()->format('y-m-d h:i:s'),
                    'tirage' => $request->input('tirage') ?? 'Tout',
                    "rapport" => $request->input('date_debut') . " au " . $request->input('date_fin'),
                    "ticket_gain" => $ticket_win,
                    "ticket_perte" => $ticket_lose,
                    "ticket_total" => $ticket_win + $ticket_lose,
                    "vente" => $vente,
                    "perte" => $perte,
                    'commission' => round($commission, 2),
                    "balance" => round($vente - ($perte + $commission), 2)


                ], 200,);
            } else {

                $tirage_record = tirage_record::where([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    ['name', '=', $request->input('tirage')]
                ])->first();
                if (!$tirage_record) {
                    return response()->json([
                        "status" => 'false',
                        "code" => '404',
                        "message" => "tirage non trouve"
                    ], 404,);
                }


                // Step 1: Fetch relevant ticket_code records based on date and compagnie_id
                // $ticketCodes = DB::table('ticket_code')
                //     ->where([
                //         ['compagnie_id', '=', auth()->user()->compagnie_id],
                //         ['user_id', '=', auth()->user()->id],
                //     ])
                //     ->whereDate('created_at', '>=', $request->input('date_debut'))
                //     ->whereDate('created_at', '<=', $request->input('date_fin'))
                //     ->pluck('code');
                $startDate = $request->input('date_debut') . ' 00:00:00';
                $endDate = $request->input('date_fin') . ' 23:59:59';
                $ticketCodes = ticket_Code::where('compagnie_id', auth()->user()->compagnie_id)
                    ->where('user_id', auth()->user()->id)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->pluck('code');
                // Step 2: Execute each query separately using the plucked ticket_code_id
                $baseQuery = DB::table('ticket_vendu')
                    ->whereIn('ticket_code_id', $ticketCodes)
                    ->where([
                        ['is_cancel', '=', 0],
                        ['is_delete', '=', 0],
                        ['pending', '=', 0],
                        ['ticket_vendu.tirage_record_id', '=', $tirage_record->id],

                    ]);

                // Calculate sums
                $vente = (clone $baseQuery)->sum('amount');
                $perte = (clone $baseQuery)->sum('winning');
                $commission = (clone $baseQuery)->sum('commission');

                // Calculate win and lose ticket counts
                $ticket_win = (clone $baseQuery)->where('is_win', 1)->count();
                $ticket_lose = (clone $baseQuery)->where('is_win', 0)->count();









                return response()->json([
                    'status' => 'true',
                    "code" => '200',
                    "date" => Carbon::now()->format('y-m-d h:i:s'),
                    'tirage' => $request->input('tirage'),
                    "rapport" => $request->input('date_debut') . " au " . $request->input('date_fin'),
                    "ticket_gain" => $ticket_win,
                    "ticket_perte" => $ticket_lose,
                    "ticket_total" => $ticket_win + $ticket_lose,
                    "vente" => $vente,
                    "perte" => $perte,
                    "commission" => round($commission, 2),
                    "balance" => round($vente - ($perte + $commission), 2)
                ], 200,);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                "message" => $th->getMessage(),

            ], 500,);
        }
    }
    public function payer_ticket(Request $request)
    {
        try {

            $validator = $request->validate([
                "id" => "required",
                "tirage" => "required",

            ]);
            $tirage_record = tirage_record::where([
                ['compagnie_id', '=', auth()->user()->compagnie_id],
                ['is_active', '=', '1'],
                ['name', '=', $request->input('tirage')]
            ])->first();
            if ($tirage_record) {
                $vente = DB::table('ticket_code')->where([
                    ['ticket_code.compagnie_id', '=', auth()->user()->compagnie_id],
                    ['ticket_code.user_id', '=', auth()->user()->id],
                    ['ticket_vendu.is_cancel', '=', 0],
                    ['ticket_vendu.is_delete', '=', 0],
                    ['ticket_vendu.ticket_code_id', '=', $request->input('id')],
                    ['ticket_vendu.tirage_record_id', '=', $tirage_record->id]

                ])->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                    ->first();
                if ($vente) {
                    $ticket_vendu = DB::table('ticket_vendu')->where([
                        ['ticket_code_id', '=', $request->input('id')],
                        ['tirage_record_id', '=', $tirage_record->id]
                    ])->update([
                        'is_payed' => '1'
                    ]);
                    return response()->json([
                        'status' => 'true',
                        "code" => '200',
                        "message" => 'ticket paye avec success'

                    ], 200,);
                } else {
                    return response()->json([
                        'status' => 'false',
                        "code" => '404',
                        "message" => 'ticket non trouve'

                    ], 404,);
                }
            } else {
                return response()->json([
                    'status' => 'false',
                    'code' => '404',
                    'message' => 'Tirage non trouver'

                ], 404,);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                "message" => $th->getMessage(),

            ], 500,);
        }
    }

    public function copier_ticket(Request $request)
    {
        try {
            $validator = $request->validate([
                "id" => "required",

            ]);
            $ticket = DB::table('ticket_code')->where([
                ['ticket_code.compagnie_id', '=', auth()->user()->compagnie_id],
                ['ticket_code.user_id', '=', auth()->user()->id],
                ['ticket_vendu.is_cancel', '=', 0],
                ['ticket_vendu.is_delete', '=', 0],
                ['ticket_code.code', '=', $request->input('id')]


            ])
                ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                ->select('ticket_code.code as ticket_id', 'ticket_vendu.boule')
                ->first();
            if ($ticket) {

                return response()->json([
                    'status' => 'true',
                    "code" => '200',
                    "body" => json_decode($ticket->boule)


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
}
