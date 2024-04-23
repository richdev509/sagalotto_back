<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\limitprixachat;
use Illuminate\Http\Request;
use App\Models\switchboul;
use App\Models\tirage;
use App\Models\tirage_record;
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
    public static function verifierLimitePrixJouer(Request $request)
    {

        $limit = limitprixachat::where([
            ['compagnie_id', '=', auth()->user()->compagnie_id]
        ])->first();
        if (!empty($request->input('bolete'))) {


            foreach ($request->input('bolete') as $montant) {
                if ($limit->boletetat == '1' && $montant['montant'] > $limit->bolet) {
                    return response()->json([
                        'status' => 'false',
                        'message' => [
                            'info' => 'pri a depase limit pou boul la',
                            'boule' => 'limit pou jwe yon boul se ' . $limit->bolet,

                        ],
                        'code' => '404'

                    ], 404,);
                }
            }
        }
        if (!empty($request->input('maryaj'))) {
            foreach ($request->input('maryaj') as $montant) {
                if ($limit->maryajetat == '1' && $montant['montant'] > $limit->maryaj) {
                    return response()->json([
                        'status' => 'false',
                        'message' => [
                            'info' => 'pri a depase limit pou maryaj la',
                            'boule' => 'limit pou jwe yon maryaj se ' . $limit->maryaj,

                        ],
                        'code' => '404'

                    ], 404,);
                }
            }
        }
        if (!empty($request->input('loto3'))) {
            foreach ($request->input('loto3') as $montant) {
                if ($limit->loto3etat == '1' && $montant['montant'] > $limit->loto3) {
                    return response()->json([
                        'status' => 'false',
                        'message' => [
                            'info' => 'pri a depase limit pou loto3',
                            'boule' => 'limit pou jwe yon loto3 se ' . $limit->loto3,

                        ],
                        'code' => '404'

                    ], 404,);
                }
            }
        }
        if (!empty($request->input('loto4'))) {

            foreach ($request->input('loto4') as $option) {
                if (!empty($option['option1'])) {
                    if ($limit->loto4etat == '1' && $option['option1'] > $limit->loto4) {
                        return response()->json([
                            'status' => 'false',
                            'message' => [
                                'info' => 'pri a depase limit pou loto4',
                                'boule' => 'limit pou jwe yon loto4 se ' . $limit->loto4,

                            ],
                            'code' => '404'

                        ], 404,);
                    }
                }
                if (!empty($option['option2'])) {
                    if ($limit->loto4etat == '1' && $option['option2'] > $limit->loto4) {
                        return response()->json([
                            'status' => 'false',
                            'message' => [
                                'info' => 'pri a depase limit pou loto4',
                                'boule' => 'limit pou jwe yon loto4 se ' . $limit->loto4,

                            ],
                            'code' => '404'

                        ], 404,);
                    }
                }
                if (!empty($option['option3'])) {

                    if ($limit->loto4etat == '1' && $option['option3'] > $limit->loto4) {
                        return response()->json([
                            'status' => 'false',
                            'message' => [
                                'info' => 'pri a depase limit pou loto4',
                                'boule' => 'limit pou jwe yon loto4 se ' . $limit->loto4,

                            ],
                            'code' => '404'

                        ], 404,);
                    }
                }
            }
        }
        if (!empty($request->input('loto5'))) {
            foreach ($request->input('loto5') as $option) {
                if (!empty($option['option1'])) {
                    if ($limit->loto5etat == '1' && $option['option1'] > $limit->loto5) {
                        return response()->json([
                            'status' => 'false',
                            'message' => [
                                'info' => 'pri a depase limit pou loto5',
                                'boule' => 'limit pou jwe yon loto5 se ' . $limit->loto5,

                            ],
                            'code' => '404'

                        ], 404,);
                    }
                }
                if (!empty($option['option2'])) {
                    if ($limit->loto5etat == '1' && $option['option2'] > $limit->loto5) {
                        return response()->json([
                            'status' => 'false',
                            'message' => [
                                'info' => 'pri a depase limit pou loto5',
                                'boule' => 'limit pou jwe yon loto3 se ' . $limit->loto5,

                            ],
                            'code' => '404'

                        ], 404,);
                    }
                }
                if (!empty($option['option3'])) {
                    if ($limit->loto5etat == '1' && $option['option3'] > $limit->loto5) {
                        return response()->json([
                            'status' => 'false',
                            'message' => [
                                'info' => 'pri a depase limit pou loto5',
                                'boule' => 'limit pou jwe yon loto5 se ' . $limit->loto5,

                            ],
                            'code' => '404'

                        ], 404,);
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
        }
        return false;
    }
}
