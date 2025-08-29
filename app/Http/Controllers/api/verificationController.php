<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\limit_auto;
use App\Models\limitprixachat;
use App\Models\limitprixboul;
use App\Models\rules_vendeur;
use Illuminate\Http\Request;
use App\Models\switchboul;
use App\Models\tirage;
use App\Models\seting;
use App\Models\tirage_record;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



class verificationController extends Controller
{

    public static function verifierBoulesNonAutorisees(Request $request)
    {
        //la varaible contenant les boules
        $boulesNonAutorisees = [];

        // dd($request->input('tirages'));
        // Parcourir les tirage
        foreach ($request->input('tirages') as $name) {
            //rechercher le nom de chak tirages
            foreach ($name as $value) {
                //prendre l'id du tirage
                $tirage_id = tirage_record::where([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    ['name', '=', $value],

                ])->first();

                foreach ($request->input('bolete') as $boule) {



                    $bouleBloquee = Switchboul::where([
                        ['id_compagnie', auth()->user()->compagnie_id],
                        ['tirage_id', '=', $tirage_id->id],
                        ['boul', '=', $boule['boul1']]

                    ])->first();

                    if ($bouleBloquee) {
                        $boulesNonAutorisees[] = $boule['boul1'] . '( ' . $value . ' )';
                    }
                }
            }
        }

        if (!empty($boulesNonAutorisees)) {
            return response()->json([
                'status' => 'false',
                'message' => [
                    'info' => 'boule bloque',
                    'boule' => $boulesNonAutorisees,

                ],
                'code' => '404'

            ], 404,);
        } else {
            return '1';
        }
    }
    public static function verifierLimitePrixJouer(Request $request, $tirage)
    {
        $limitePrixJouer = [];
        $limit = limitprixachat::where([
            ['compagnie_id', '=', auth()->user()->compagnie_id]
        ])->first();


        if (!empty($request->input('bolete'))) {

            foreach ($request->input('bolete') as $montant) {
                $amount_bo = 0;
                $amount_bo = limit_auto::where([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    ['tirage', '=', $tirage],
                    ['boule', '=', $montant['boul1']],
                    ['type', '=', 'bolet']
                ])->whereDate('created_at', '=', Carbon::today())
                    ->sum('amount');
                if ($limit->boletetat == '1' && ($montant['montant'] + $amount_bo) > $limit->bolet) {

                    $limitePrixJouer[] = 'limit ' . $montant['boul1'] . ' nan ' . $tirage;
                }
            }
        }
        if (!empty($request->input('maryaj'))) {


            foreach ($request->input('maryaj') as $montant) {
                $amount_ma = 0;
                $amount_ma = limit_auto::where([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    ['tirage', '=', $tirage],
                    ['boule', '=', $montant['boul1'] . $montant['boul2']],
                    ['type', '=', 'maryaj']
                ])->orwhere([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    ['tirage', '=', $tirage],
                    ['boule', '=', $montant['boul2'] . $montant['boul1']],
                    ['type', '=', 'maryaj']
                ])->whereDate('created_at', '=', Carbon::today())
                    ->sum('amount');


                if ($limit->maryajetat == '1' && ($montant['montant'] + $amount_ma) > $limit->maryaj) {

                    $limitePrixJouer[] = 'limit ' . $montant['boul1'] . $montant['boul2'] . ' nan ' . $tirage;
                }
            }
        }
        if (!empty($request->input('loto3'))) {
            foreach ($request->input('loto3') as $montant) {
                $amount_l3 = 0;
                $amount_l3 = limit_auto::where([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    ['tirage', '=', $tirage],
                    ['boule', '=', $montant['boul1']],
                    ['type', '=', 'loto3']
                ])->whereDate('created_at', '=', Carbon::today())
                    ->sum('amount');
                if ($limit->loto3etat == '1' && ($montant['montant'] + $amount_l3) > $limit->loto3) {

                    $limitePrixJouer[] = 'limit ' . $montant['boul1'] . ' nan ' . $tirage;
                }
            }
        }
        if (!empty($request->input('loto4'))) {

            foreach ($request->input('loto4') as $option) {
                if (!empty($option['option1'])) {
                    $amount_op1 = 0;
                    $amount_op1 = limit_auto::where([
                        ['compagnie_id', '=', auth()->user()->compagnie_id],
                        ['tirage', '=', $tirage],
                        ['boule', '=', $option['boul1']],
                        ['type', '=', 'loto4'],
                        ['opsyon', '=', 1],
                    ])->whereDate('created_at', '=', Carbon::today())
                        ->sum('amount');
                    if ($limit->loto4etat == '1' && ($option['option1'] + $amount_op1) > $limit->loto4) {


                        $limitePrixJouer[]  = 'limit ' . $option['boul1'] . '(1)' . ' nan ' . $tirage;
                    }
                }
                if (!empty($option['option2'])) {
                    $amount_op2 = 0;
                    $amount_op2 = limit_auto::where([
                        ['compagnie_id', '=', auth()->user()->compagnie_id],
                        ['tirage', '=', $tirage],
                        ['boule', '=', $option['boul1']],
                        ['type', '=', 'loto4'],
                        ['opsyon', '=', 2],
                    ])->whereDate('created_at', '=', Carbon::today())
                        ->sum('amount');
                    if ($limit->loto4etat == '1' && ($option['option2'] + $amount_op2) > $limit->loto4) {


                        $limitePrixJouer[]  = 'limit ' . $option['boul1'] . '(2)' . ' nan ' . $tirage;
                    }
                }
                if (!empty($option['option3'])) {
                    $amount_op3 = 0;
                    $amount_op3 = limit_auto::where([
                        ['compagnie_id', '=', auth()->user()->compagnie_id],
                        ['tirage', '=', $tirage],
                        ['boule', '=', $option['boul1']],
                        ['type', '=', 'loto4'],
                        ['opsyon', '=', 3],
                    ])->whereDate('created_at', '=', Carbon::today())
                        ->sum('amount');
                    if ($limit->loto4etat == '1' && ($option['option3'] + $amount_op3) > $limit->loto4) {


                        $limitePrixJouer[]  = 'limit ' . $option['boul1'] . '(3)' . ' nan ' . $tirage;
                    }
                }
            }
        }
        if (!empty($request->input('loto5'))) {

            foreach ($request->input('loto5') as $option) {


                if (!empty($option['option1'])) {
                    $amount_l51 = 0;
                    $amount_l51 = limit_auto::where([
                        ['compagnie_id', '=', auth()->user()->compagnie_id],
                        ['tirage', '=', $tirage],
                        ['boule', '=', $option['boul1']],
                        ['type', '=', 'loto5'],
                        ['opsyon', '=', 1]
                    ])->whereDate('created_at', '=', Carbon::today())
                        ->sum('amount');
                    if ($limit->loto5etat == '1' && ($option['option1'] + $amount_l51) > $limit->loto5) {

                        $limitePrixJouer[] = 'limit ' . $option['boul1'] . '(1)' . ' nan ' . $tirage;
                    }
                }
                if (!empty($option['option2'])) {
                    $amount_l52 = 0;
                    $amount_l52 = limit_auto::where([
                        ['compagnie_id', '=', auth()->user()->compagnie_id],
                        ['tirage', '=', $tirage],
                        ['boule', '=', $option['boul1']],
                        ['type', '=', 'loto5'],
                        ['opsyon', '=', 2]
                    ])->whereDate('created_at', '=', Carbon::today())
                        ->sum('amount');
                    if ($limit->loto5etat == '1' && ($option['option2'] + $amount_l52) > $limit->loto5) {

                        $limitePrixJouer[] = 'limit ' . $option['boul1'] . '(2)' . ' nan ' . $tirage;
                    }
                }
                if (!empty($option['option3'])) {
                    $amount_l53 = 0;
                    $amount_l53 = limit_auto::where([
                        ['compagnie_id', '=', auth()->user()->compagnie_id],
                        ['tirage', '=', $tirage],
                        ['boule', '=', $option['boul1']],
                        ['type', '=', 'loto5'],
                        ['opsyon', '=', 3]
                    ])->whereDate('created_at', '=', Carbon::today())
                        ->sum('amount');
                    if ($limit->loto5etat == '1' && ($option['option3'] + $amount_l53) > $limit->loto5) {

                        $limitePrixJouer[] = 'limit ' . $option['boul1'] . '(3)' . ' nan ' . $tirage;
                    }
                }
            }
        }
        if (!empty($limitePrixJouer)) {
            return response()->json([
                'status' => 'false',
                'message' => [
                    'info' => 'pri a depase limit la',
                    'boule' => $limitePrixJouer,


                ],
                'code' => '404'

            ], 404,);
        } else {
            return '1';
        }
    }
    public static function StockerLimitePrixJouer(Request $request)
    {
        foreach ($request->input('tirages') as $tirage) {
            if (!empty($request->input('bolete'))) {

                foreach ($request->input('bolete') as $montant) {
                    $query = DB::table('limit_autos')->insert([
                        'compagnie_id' => auth()->user()->compagnie_id,
                        'tirage' => $tirage['name'],
                        'boule' => $montant['boul1'],
                        'amount' =>  $montant['montant'],
                        'type' => 'bolet',

                        'created_at' => Carbon::now(),
                    ]);
                }
            }
            if (!empty($request->input('maryaj'))) {

                foreach ($request->input('maryaj') as $montant) {
                    $query = DB::table('limit_autos')->insert([
                        'compagnie_id' => auth()->user()->compagnie_id,
                        'tirage' => $tirage['name'],
                        'boule' => $montant['boul1'] . $montant['boul2'],
                        'amount' =>  $montant['montant'],
                        'type' => 'maryaj',

                        'created_at' => Carbon::now(),
                    ]);
                }
            }
            if (!empty($request->input('loto3'))) {
                foreach ($request->input('loto3') as $montant) {
                    $query = DB::table('limit_autos')->insert([
                        'compagnie_id' => auth()->user()->compagnie_id,
                        'tirage' => $tirage['name'],
                        'boule' => $montant['boul1'],
                        'amount' =>  $montant['montant'],
                        'type' => 'loto3',

                        'created_at' => Carbon::now(),
                    ]);
                }
            }
            if (!empty($request->input('loto4'))) {

                foreach ($request->input('loto4') as $option) {

                    if (!empty($option['option1'])) {
                        $query = DB::table('limit_autos')->insert([
                            'compagnie_id' => auth()->user()->compagnie_id,
                            'tirage' => $tirage['name'],
                            'boule' => $option['boul1'],
                            'amount' =>  $option['option1'],
                            'type' => 'loto4',
                            'opsyon' => 1,
                            'created_at' => Carbon::now(),
                        ]);
                    }
                    if (!empty($option['option2'])) {
                        $query = DB::table('limit_autos')->insert([
                            'compagnie_id' => auth()->user()->compagnie_id,
                            'tirage' => $tirage['name'],
                            'boule' => $option['boul1'],
                            'amount' =>  $option['option2'],
                            'type' => 'loto4',
                            'opsyon' => 2,
                            'created_at' => Carbon::now(),
                        ]);
                    }
                    if (!empty($option['option3'])) {
                        $query = DB::table('limit_autos')->insert([
                            'compagnie_id' => auth()->user()->compagnie_id,
                            'tirage' => $tirage['name'],
                            'boule' => $option['boul1'],
                            'amount' =>  $option['option3'],
                            'type' => 'loto4',
                            'opsyon' => 3,
                            'created_at' => Carbon::now(),
                        ]);
                    }
                }
            }
            if (!empty($request->input('loto5'))) {

                foreach ($request->input('loto5') as $option) {

                    if (!empty($option['option1'])) {
                        $query = DB::table('limit_autos')->insert([
                            'compagnie_id' => auth()->user()->compagnie_id,
                            'tirage' => $tirage['name'],
                            'boule' => $option['boul1'],
                            'amount' =>  $option['option1'],
                            'type' => 'loto5',
                            'opsyon' => 1,
                            'created_at' => Carbon::now(),
                        ]);
                    }
                    if (!empty($option['option2'])) {
                        $query = DB::table('limit_autos')->insert([
                            'compagnie_id' => auth()->user()->compagnie_id,
                            'tirage' => $tirage['name'],
                            'boule' => $option['boul1'],
                            'amount' =>  $option['option2'],
                            'type' => 'loto5',
                            'opsyon' => 2,
                            'created_at' => Carbon::now(),
                        ]);
                    }
                    if (!empty($option['option3'])) {
                        $query = DB::table('limit_autos')->insert([
                            'compagnie_id' => auth()->user()->compagnie_id,
                            'tirage' => $tirage['name'],
                            'boule' => $option['boul1'],
                            'amount' =>  $option['option3'],
                            'type' => 'loto5',
                            'opsyon' => 3,
                            'created_at' => Carbon::now(),
                        ]);
                    }
                }
            }
        }
    }
    public static function verifierLimitePrixBoule(Request $request, $tirage)
    {

        if (!empty($request->input('bolete'))) {


            foreach ($request->input('bolete') as $value) {
                $limit_boul = limitprixboul::where([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    ['opsyon', '=', 'Bolet'],
                    ['type', '=', $tirage],
                    ['boul', '=', $value['boul1']]


                ])->first();
                if ($limit_boul) {
                    $amount_bo = 0;
                    $amount_bo = limit_auto::where([
                        ['compagnie_id', '=', auth()->user()->compagnie_id],
                        ['tirage', '=', $tirage],
                        ['boule', '=', $value['boul1']],
                        ['type', '=', 'bolet']
                    ])->whereDate('created_at', '=', Carbon::today())
                        ->sum('amount');

                    if ($amount_bo +  $value['montant'] > $limit_boul->montant) {
                        return response()->json([
                            'status' => 'false',
                            'message' => [
                                'info' => 'pri a depase limit pou boul sa',
                                'boule' => 'ou paka jwe ' . $value['boul1'] . ' pou plis ke ' . $limit_boul->montant - $amount_bo,

                            ],
                            'code' => '404'

                        ], 404,);
                    }
                }
            }
        }
        if (!empty($request->input('maryaj'))) {

            foreach ($request->input('maryaj') as $value) {
                $limit_boul = limitprixboul::where([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    ['opsyon', '=', 'Maryaj'],
                    ['type', '=', $tirage],
                    ['boul', '=', $value['boul1'] . $value['boul2']]

                ])->orwhere([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    ['opsyon', '=', 'Maryaj'],
                    ['type', '=', $tirage],
                    ['boul', '=', $value['boul2'] . $value['boul1']]

                ])->first();
                if ($limit_boul) {
                    $amount_ma = 0;
                    $amount_ma = limit_auto::where([
                        ['compagnie_id', '=', auth()->user()->compagnie_id],
                        ['tirage', '=', $tirage],
                        ['boule', '=', $value['boul1'] . $value['boul2']],
                        ['type', '=', 'maryaj']
                    ])->orwhere([
                        ['compagnie_id', '=', auth()->user()->compagnie_id],
                        ['tirage', '=', $tirage],
                        ['boule', '=', $value['boul2'] . $value['boul1']],
                        ['type', '=', 'maryaj']
                    ])->whereDate('created_at', '=', Carbon::today())
                        ->sum('amount');
                    if ($amount_ma + $value['montant'] > $limit_boul->montant) {
                        return response()->json([
                            'status' => 'false',
                            'message' => [
                                'info' => 'pri a depase limit pou boul sa',
                                'boule' => 'ou paka jwe ' . $value['boul1'] . 'x' . $value['boul2'] . ' pou plis ke ' . $limit_boul->montant - $amount_ma,

                            ],
                            'code' => '404'

                        ], 404,);
                    }
                }
            }
        }
        if (!empty($request->input('loto3'))) {
            foreach ($request->input('loto3') as $value) {
                $limit_boul = limitprixboul::where([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    ['opsyon', '=', 'Loto3'],
                    ['type', '=', $tirage],
                    ['boul', '=', $value['boul1']]


                ])->first();
                if ($limit_boul) {
                    $amount_l3 = 0;
                    $amount_l3 = limit_auto::where([
                        ['compagnie_id', '=', auth()->user()->compagnie_id],
                        ['tirage', '=', $tirage],
                        ['boule', '=', $value['boul1']],
                        ['type', '=', 'loto3']
                    ])->whereDate('created_at', '=', Carbon::today())
                        ->sum('amount');

                    if ($amount_l3 + $value['montant'] > $limit_boul->montant) {
                        return response()->json([
                            'status' => 'false',
                            'message' => [
                                'info' => 'pri a depase limit pou boul sa',
                                'boule' => 'ou paka jwe ' . $value['boul1'] . ' pou plis ke ' . $limit_boul->montant - $amount_l3,

                            ],
                            'code' => '404'

                        ], 404,);
                    }
                }
            }
        }
        if (!empty($request->input('loto4'))) {

            foreach ($request->input('loto4') as $value) {
                $limit_boul = limitprixboul::where([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    ['opsyon', '=', 'Loto4'],
                    ['type', '=', $tirage],
                    ['boul', '=', $value['boul1']]


                ])->first();
                if (!empty($value['option1'])) {

                    if ($limit_boul) {
                        if ($limit_boul->montant < $value['option1']) {
                            return response()->json([
                                'status' => 'false',
                                'message' => [
                                    'info' => 'pri a depase limit pou boul sa',
                                    'boule' => 'ou paka jwe ' . $value['boul1'] . ' pou plis ke ' . $limit_boul->montant,

                                ],
                                'code' => '404'

                            ], 404,);
                        }
                        if ($limit_boul->is_general == 1) {
                            $limit_boul->montant = $limit_boul->montant - $value['option1'];
                            $limit_boul->save();
                        }
                    }
                }

                if (!empty($value['option2'])) {

                    if ($limit_boul) {

                        if ($limit_boul->montant < $value['option2']) {
                            return response()->json([
                                'status' => 'false',
                                'message' => [
                                    'info' => 'pri a depase limit pou boul sa',
                                    'boule' => 'ou paka jwe ' . $value['boul1'] . ' pou plis ke ' . $limit_boul->montant,

                                ],
                                'code' => '404'

                            ], 404,);
                        }
                        if ($limit_boul->is_general == 1) {
                            $limit_boul->montant = $limit_boul->montant - $value['option2'];
                            $limit_boul->save();
                        }
                    }
                }
                if (!empty($value['option3'])) {
                    if ($limit_boul) {

                        if ($limit_boul->montant < $value['option3']) {
                            return response()->json([
                                'status' => 'false',
                                'message' => [
                                    'info' => 'pri a depase limit pou boul sa',
                                    'boule' => 'ou paka jwe ' . $value['boul1'] . ' pou plis ke ' . $limit_boul->montant,
                                ],
                                'code' => '404'

                            ], 404,);
                        }
                        if ($limit_boul->is_general == 1) {
                            $limit_boul->montant = $limit_boul->montant - $value['option3'];
                            $limit_boul->save();
                        }
                    }
                }
            }
        }
        if (!empty($request->input('loto5'))) {
            foreach ($request->input('loto5') as $value) {
                $limit_boul = limitprixboul::where([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    ['opsyon', '=', 'Loto5'],
                    ['type', '=', $tirage],
                    ['boul', '=', $value['boul1']]


                ])->first();
                if (!empty($value['option1'])) {

                    if ($limit_boul) {

                        if ($limit_boul->montant < $value['option1']) {
                            return response()->json([
                                'status' => 'false',
                                'message' => [
                                    'info' => 'pri a depase limit pou boul sa',
                                    'boule' => 'ou paka jwe ' . $value['boul1'] . ' pou plis ke ' . $limit_boul->montant,

                                ],
                                'code' => '404'

                            ], 404,);
                        }
                        if ($limit_boul->is_general == 1) {
                            $limit_boul->montant = $limit_boul->montant - $value['option1'];
                            $limit_boul->save();
                        }
                    }
                }

                if (!empty($value['option2'])) {

                    if ($limit_boul) {

                        if ($limit_boul->montant < $value['option2']) {
                            return response()->json([
                                'status' => 'false',
                                'message' => [
                                    'info' => 'pri a depase limit pou boul sa',
                                    'boule' => 'ou paka jwe ' . $value['boul1'] . ' pou plis ke ' . $limit_boul->montant,

                                ],
                                'code' => '404'

                            ], 404,);
                        }
                        if ($limit_boul->is_general == 1) {
                            $limit_boul->montant = $limit_boul->montant - $value['option2'];
                            $limit_boul->save();
                        }
                    }
                }
                if (!empty($value['option3'])) {
                    if ($limit_boul) {

                        if ($limit_boul->montant < $value['option3']) {
                            return response()->json([
                                'status' => 'false',
                                'message' => [
                                    'info' => 'pri a depase limit pou boul sa',
                                    'boule' => 'ou paka jwe ' . $value['boul1'] . ' pou plis ke ' . $limit_boul->montant,
                                ],
                                'code' => '404'

                            ], 404,);
                        }
                        if ($limit_boul->is_general == 1) {
                            $limit_boul->montant = $limit_boul->montant - $value['option3'];
                            $limit_boul->save();
                        }
                    }
                }
            }
        }
        return '1';
    }
    public static function calculer_montant(Request $request)
    {
        //le montant total
        $montant_tot = 0;
        if (!empty($request->input('bolete'))) {

            foreach ($request->input('bolete') as $montant) {
                $montant_tot = $montant_tot + $montant['montant'];
            }
        }
        if (!empty($request->input('maryaj'))) {
            foreach ($request->input('maryaj') as $montant) {
                $montant_tot = $montant_tot + $montant['montant'];
            }
        }
        if (!empty($request->input('loto3'))) {
            foreach ($request->input('loto3') as $montant) {
                $montant_tot = $montant_tot + $montant['montant'];
            }
        }
        if (!empty($request->input('loto4'))) {

            foreach ($request->input('loto4') as $option) {
                if (!empty($option['option1'])) {
                    $montant_tot = $montant_tot + $option['option1'];
                }
                if (!empty($option['option2'])) {
                    $montant_tot = $montant_tot + $option['option2'];
                }
                if (!empty($option['option3'])) {
                    $montant_tot = $montant_tot + $option['option3'];
                }
            }
        }
        if (!empty($request->input('loto5'))) {
            foreach ($request->input('loto5') as $option) {
                if (!empty($option['option1'])) {
                    $montant_tot = $montant_tot + $option['option1'];
                }
                if (!empty($option['option2'])) {
                    $montant_tot = $montant_tot + $option['option2'];
                }
                if (!empty($option['option3'])) {
                    $montant_tot = $montant_tot + $option['option3'];
                }
            }
        }



        return $montant_tot;
    }
    public static function generer_gratuit($data, $montant, $tirage_name)
    {

        // Explode the input string into an array of words
        $words = explode(' ', $tirage_name);

        // Use Laravel's Collection class for easy manipulation
        $result = collect($words)->map(function ($word) {
            // Get the first two characters of each word
            return substr($word, 0, 1);
        });
      
        $setting = seting::where([
            ['compagnie_id', '=', auth()->user()->compagnie_id],
        ])->first();
        $ma_price ='Gagnant';
        if ($setting) {
           if($setting->show_mariage_price=='1'){

            $vendeur = rules_vendeur::where('user_id', auth()->user()->id)->first();
               if ($vendeur) {
                   $ma_price = $vendeur->prix_maryaj_gratis.' G';
               }else{
                   $ma_price = $data->prix.' G';
               }
            }
        }

        // Return the result as a string
        $tirage_name = $result->implode(' ');
        $tirage_name = str_replace(' ', '', $tirage_name);
        if ($data->min_inter_1 <= $montant && $data->max_inter_1 >= $montant) {

            for ($i = 1; $i <= $data->q_inter_1; $i++) {
                $mariage[] = [
                    'boul1' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'boul2' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'montant' => $tirage_name . ' ' .$ma_price,
                ];
            }
            return $mariage;
        } elseif ($data->min_inter_2 <= $montant && $data->max_inter_2 >= $montant) {

            for ($i = 1; $i <= $data->q_inter_2; $i++) {
                $mariage[] = [
                    'boul1' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'boul2' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'montant' => $tirage_name . ' ' .$ma_price,

                ];
            }

            return $mariage;
        } elseif ($data->min_inter_3 <= $montant && $data->max_inter_3 >= $montant) {

            for ($i = 1; $i <= $data->q_inter_3; $i++) {
                $mariage[] = [
                    'boul1' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'boul2' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'montant' => $tirage_name . ' ' .$ma_price,

                ];
            }

            return $mariage;
        } elseif ($data->min_inter_4 <= $montant && $data->max_inter_4 >= $montant) {

            for ($i = 1; $i <= $data->q_inter_4; $i++) {
                $mariage[] = [
                    'boul1' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'boul2' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'montant' => $tirage_name . ' ' .$ma_price,

                ];
            }

            return $mariage;
        } elseif ($data->min_inter_5 <= $montant && $data->max_inter_5 >= $montant) {

            for ($i = 1; $i <= $data->q_inter_5; $i++) {
                $mariage[] = [
                    'boul1' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'boul2' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'montant' => $tirage_name . ' ' .$ma_price,

                ];
            }

            return $mariage;
        } elseif ($data->min_inter_6 <= $montant && $data->max_inter_6 >= $montant) {

            for ($i = 1; $i <= $data->q_inter_6; $i++) {
                $mariage[] = [
                    'boul1' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'boul2' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'montant' => $tirage_name . ' ' .$ma_price,

                ];
            }

            return $mariage;
        }
        return false;
    }

    public static function removeZeroOptions($request)
    {
        $data = $request->all();
        if (!empty($data['loto4'])) {
            $data['loto4'] = array_map(function ($item) {
                return array_filter($item, function ($value, $key) {
                    return !($key === 'option1' && ($value == 0 || is_null($value))) &&
                        !($key === 'option2' && ($value == 0 || is_null($value))) &&
                        !($key === 'option3' && ($value == 0 || is_null($value)));
                }, ARRAY_FILTER_USE_BOTH);
            }, $data['loto4']);
        }

        return $data;
    }
    public static function limiteNumberPlay( $request, $setting)
    {   
        $error = [];

        // Decode the JSON input
        $data = json_decode($request->getContent(), true);

        // Check if 'bolete' exists and count the unique groups
        $boleteGroups = isset($data['bolete']) ? count($data['bolete']) : 0;
        $maryajGroups = isset($data['maryaj']) ? count($data['maryaj']) : 0;
        $loto3Groups = isset($data['loto3']) ? count($data['loto3']) : 0;
        $loto4Groups = isset($data['loto4']) ? count($data['loto4']) : 0;
        $loto5Groups = isset($data['loto5']) ? count($data['loto5']) : 0;
        if($boleteGroups > $setting->qt_bolet){
          
                 $error []=    'Kantite bolet  pa fich Limite a '. $setting->qt_bolet;

        }
        if ($maryajGroups > $setting->qt_maryaj) {
            $error []=    'Kantite maryaj  pa fich Limite a '. $setting->maryaj;

        } 

        if ($loto3Groups > $setting->qt_loto3) {
            $error []=    'Kantite loto3  pa fich Limite  '. $setting->loto3;

        }

        if ($loto4Groups > $setting->qt_loto4) {
            $error []=    'Kantite loto4 pa fich Limite a '. $setting->loto3;

        }

        if ($loto5Groups > $setting->qt_loto5) {
            $error []=    'Kantite loto5  pa fich Limite a '. $setting->loto5;
            
        }

        if (!empty($error)) {
            return response()->json([
                'status' => 'false',
                'message' => [
                    'info' => 'erreur kantite jwet yo limite',
                    'boule' => $error,


                ],
                'code' => '404'

            ], 404,);
        } else {
            return '1';
        }
    }
}
