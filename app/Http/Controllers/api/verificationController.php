<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\limit_auto;
use App\Models\limitprixachat;
use App\Models\limitprixboul;
use Illuminate\Http\Request;
use App\Models\switchboul;
use App\Models\tirage;
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
                        $boulesNonAutorisees[] = $boule['boul1'];
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
                $play_amount = limit_auto::where([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    ['tirage', '=', $tirage],
                    ['boule', '=', $montant['boul1']],
                    ['type', '=', 'bolet']
                ])->whereDate('created_at', '=', Carbon::now())
                    ->sum('amount');

                if ($limit->boletetat == '1' && ($montant['montant'] + $play_amount) > $limit->bolet) {

                    $limitePrixJouer[] = 'limit ' . $montant['boul1'] . ' nan ' . $tirage . ' se ' . $limit->bolet - $play_amount . ' G';
                }
            }
        }
        if (!empty($request->input('maryaj'))) {


            foreach ($request->input('maryaj') as $montant) {
                $play_amount = limit_auto::where([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    ['tirage', '=', $tirage],
                    ['boule', '=', $montant['boul1'] . $montant['boul2']],
                    ['type', '=', 'maryaj']
                ])->orwhere([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    ['tirage', '=', $tirage],
                    ['boule', '=', $montant['boul2'] . $montant['boul1']],
                    ['type', '=', 'maryaj']
                ])->whereDate('created_at', '=', Carbon::now())
                    ->sum('amount');
                if ($limit->maryajetat == '1' && $montant['montant'] + $play_amount > $limit->maryaj) {

                    $limitePrixJouer[] = 'limit ' . $montant['boul1'] . $montant['boul2'] . ' nan ' . $tirage . ' se ' . $limit->maryaj -  $play_amount . ' G';
                }
            }
        }
        if (!empty($request->input('loto3'))) {
            foreach ($request->input('loto3') as $montant) {
                $play_amount = limit_auto::where([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    ['tirage', '=', $tirage],
                    ['boule', '=', $montant['boul1']],
                    ['type', '=', 'loto3']
                ])->whereDate('created_at', '=', Carbon::now())
                ->sum('amount');
                if ($limit->loto3etat == '1' && $montant['montant'] + $play_amount > $limit->loto3) {

                    $limitePrixJouer[] = 'limit ' . $montant['boul1'] . ' nan ' . $tirage . ' se ' . $limit->loto3 - $play_amount . ' G';
                }
            }
        }
        if (!empty($request->input('loto4'))) {

            foreach ($request->input('loto4') as $option) {
                $option_c = 0;
                $play_amount = limit_auto::where([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    ['tirage', '=', $tirage],
                    ['boule', '=', $option['boul1']],
                    ['type', '=', 'loto4']
                ])->whereDate('created_at', '=', Carbon::now())
                    ->sum('amount');
                if (!empty($option['option1'])) {
                    $option_c = $option_c + $option['option1'];
                }
                if (!empty($option['option2'])) {
                    $option_c = $option_c + $option['option2'];
                }
                if (!empty($option['option3'])) {
                    $option_c = $option_c + $option['option3'];
                }


                if ($limit->loto4etat == '1' && $option_c + $play_amount > $limit->loto4) {


                    $limitePrixJouer[] = 'limit ' . $option['boul1'] . ' nan ' . $tirage . ' se ' . $limit->loto4 -  $play_amount . ' G ';
                }
            }
        }
        if (!empty($request->input('loto5'))) {

            foreach ($request->input('loto5') as $option) {
                $option_c = 0;
                $play_amount = limit_auto::where([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    ['tirage', '=', $tirage],
                    ['boule', '=', $option['boul1']],
                    ['type', '=', 'loto5']
                ])->whereDate('created_at', '=', Carbon::now())
                    ->sum('amount');
                if (!empty($option['option1'])) {
                    $option_c = $option_c + $option['option1'];
                }
                if (!empty($option['option2'])) {
                    $option_c = $option_c + $option['option2'];
                }
                if (!empty($option['option3'])) {
                    $option_c = $option_c + $option['option3'];
                }


                if ($limit->loto5etat == '1' && $option_c + $play_amount > $limit->loto5) {

                    $limitePrixJouer[] = 'limit ' . $option['boul1'] . ' nan ' . $tirage . ' se ' . $limit->loto5 - $play_amount . ' G';
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
    public static function StockerLimitePrixJouer(Request $request, $tirage)
    {
        if (!empty($request->input('bolete'))) {

            foreach ($request->input('bolete') as $montant) {
                $query = DB::table('limit_autos')->insert([
                    'compagnie_id' => auth()->user()->compagnie_id,
                    'tirage' => $tirage,
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
                    'tirage' => $tirage,
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
                    'tirage' => $tirage,
                    'boule' => $montant['boul1'],
                    'amount' =>  $montant['montant'],
                    'type' => 'loto3',

                    'created_at' => Carbon::now(),
                ]);
            }
        }
        if (!empty($request->input('loto4'))) {

            foreach ($request->input('loto4') as $option) {
                $option_c = 0;

                if (!empty($option['option1'])) {
                    $option_c = $option_c + $option['option1'];
                }
                if (!empty($option['option2'])) {
                    $option_c = $option_c + $option['option2'];
                }
                if (!empty($option['option3'])) {
                    $option_c = $option_c + $option['option3'];
                }
                $query = DB::table('limit_autos')->insert([
                    'compagnie_id' => auth()->user()->compagnie_id,
                    'tirage' => $tirage,
                    'boule' => $option['boul1'],
                    'amount' =>  $option_c,
                    'type' => 'loto4',

                    'created_at' => Carbon::now(),
                ]);
            }
        }
        if (!empty($request->input('loto5'))) {

            foreach ($request->input('loto5') as $option) {
                $option_c = 0;

                if (!empty($option['option1'])) {
                    $option_c = $option_c + $option['option1'];
                }
                if (!empty($option['option2'])) {
                    $option_c = $option_c + $option['option2'];
                }
                if (!empty($option['option2'])) {
                    $option_c = $option_c + $option['option3'];
                }
                $query = DB::table('limit_autos')->insert([
                    'compagnie_id' => auth()->user()->compagnie_id,
                    'tirage' => $tirage,
                    'boule' => $option['boul1'],
                    'amount' =>  $option_c,
                    'type' => 'loto5',

                    'created_at' => Carbon::now(),
                ]);
            }
        }

        return '1';
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

                    if ($limit_boul->montant < $value['montant']) {
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
                        $limit_boul->montant = $limit_boul->montant - $value['montant'];
                        $limit_boul->save();
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

                    if ($limit_boul->montant < $value['montant']) {
                        return response()->json([
                            'status' => 'false',
                            'message' => [
                                'info' => 'pri a depase limit pou boul sa',
                                'boule' => 'ou paka jwe ' . $value['boul1'] . 'x' . $value['boul2'] . ' pou plis ke ' . $limit_boul->montant,

                            ],
                            'code' => '404'

                        ], 404,);
                    }
                    if ($limit_boul->is_general == 1) {
                        $limit_boul->montant = $limit_boul->montant - $value['montant'];
                        $limit_boul->save();
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

                    if ($limit_boul->montant < $value['montant']) {
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
                        $limit_boul->montant = $limit_boul->montant - $value['montant'];
                        $limit_boul->save();
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
            return substr($word, 0, 2);
        });

        // Return the result as a string
        $tirage_name = $result->implode(' ');
        if ($data->min_inter_1 <= $montant && $data->max_inter_1 >= $montant) {

            for ($i = 1; $i <= $data->q_inter_1; $i++) {
                $mariage[] = [
                    'boul1' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'boul2' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'montant' => $tirage_name,
                ];
            }
            return $mariage;
        } elseif ($data->min_inter_2 <= $montant && $data->max_inter_2 >= $montant) {

            for ($i = 1; $i <= $data->q_inter_2; $i++) {
                $mariage[] = [
                    'boul1' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'boul2' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'montant' => $tirage_name,
                ];
            }

            return $mariage;
        } elseif ($data->min_inter_3 <= $montant && $data->max_inter_3 >= $montant) {

            for ($i = 1; $i <= $data->q_inter_3; $i++) {
                $mariage[] = [
                    'boul1' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'boul2' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'montant' => $tirage_name,
                ];
            }

            return $mariage;
        } elseif ($data->min_inter_4 <= $montant && $data->max_inter_4 >= $montant) {

            for ($i = 1; $i <= $data->q_inter_4; $i++) {
                $mariage[] = [
                    'boul1' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'boul2' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'montant' => $tirage_name,
                ];
            }

            return $mariage;
        } elseif ($data->min_inter_5 <= $montant && $data->max_inter_5 >= $montant) {

            for ($i = 1; $i <= $data->q_inter_5; $i++) {
                $mariage[] = [
                    'boul1' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'boul2' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'montant' => $tirage_name,
                ];
            }

            return $mariage;
        } elseif ($data->min_inter_6 <= $montant && $data->max_inter_6 >= $montant) {

            for ($i = 1; $i <= $data->q_inter_6; $i++) {
                $mariage[] = [
                    'boul1' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'boul2' => $randomNumber = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT),
                    'montant' => $tirage_name,
                ];
            }

            return $mariage;
        }
        return false;
    }

    public static function removeZeroOptions($request)
    {
        $data = $request;
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
    public static function  removeDuplicates($data)
    {


        // Retrieve JSON data from request
        $jsonData = $data->json()->all();

        // Function to filter unique boul1 values
        $filterUniqueBoul1 = function ($items) {
            $seen = [];
            return array_values(array_filter($items, function ($item) use (&$seen) {
                if (isset($item['boul1']) && !in_array($item['boul1'], $seen)) {
                    $seen[] = $item['boul1'];
                    return true;
                }
                return false;
            }));
        };
        $filterMaryaj = function ($items) {
            $seen = [];
            return array_values(array_filter($items, function ($item) use (&$seen) {
                $identifier1 = $item['boul1'] . '-' . $item['boul2'];
                $identifier2 = $item['boul2'] . '-' . $item['boul1'];
                if (isset($item['boul1']) && isset($item['boul2']) && !in_array($identifier1, $seen) && !in_array($identifier2, $seen)) {
                    $seen[] = $identifier1;
                    $seen[] = $identifier2;
                    return true;
                }
                return false;
            }));
        };

        // Apply filter to each data collection if needed
        foreach ($jsonData as $key => $collection) {
            if ($key === 'maryaj') {
                $jsonData[$key] = $filterMaryaj($collection);
            } else {
                if (is_array($collection)) {
                    $jsonData[$key] = $filterUniqueBoul1($collection);
                }
            }
        }

        // Example: Using additional parameters ($param1 and $param2)
        // You can use $param1 and $param2 here for any specific logic

        // Return filtered data as JSON response
        return $jsonData;
        
    }
}
