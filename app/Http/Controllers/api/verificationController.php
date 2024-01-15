<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\switchboul;
use App\Models\tirage;
use App\Models\tirage_record;


class verificationController extends Controller
{

    public static function verifierBoulesNonAutorisees(Request $request)
    {
        //la varaible contenant les boules
        $boulesNonAutorisees = [];


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

                    ])
                        ->first();

                    if ($bouleBloquee) {
                        $boulesNonAutorisees[] = $boule['boul1'];
                    }
                }
            }
        }

        if (!empty($boulesNonAutorisees)) {
            return response()->json([
                'status' => 'false',
                'message' =>[
                    'info'=>'boule bloque',
                    'boule'=>$boulesNonAutorisees,
                
                ],
                'code' => '404'

            ], 404,);
        } else {
            return '1';
        }
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

            foreach ($request->input('loto4') as $montant) {
                $montant_tot = $montant_tot + $montant['montant'];
            }
        }
        if (!empty($request->input('loto5'))) {
            foreach ($request->input('loto5') as $montant) {
                $montant_tot = $montant_tot + $montant['montant'];
            }
        }



        return $montant_tot;
    }
}
