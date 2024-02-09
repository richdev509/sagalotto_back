<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\maryajgratis;
use App\Models\RulesOne;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class parametreController extends Controller
{

    public  function indexmaryaj()
    {
        $data = maryajgratis::where('compagnie_id', session('loginId'))->first();
        return view('parametre.maryajGratis', compact('data'));
    }


    public function store()
    {
        try {

            $repons = maryajgratis::create([
                'prix' => 0,
                'compagnie_id' => session('loginId'),
                'etat' => 1,
            ]);

            return $repons;
        } catch (\Exception $e) {
            // Gérer l'exception si la mise à jour échoue
            return false;
        }
    }

    public  function updatestatut(Request $request)
    {
        $statut = $request->status;
        $reponse = maryajgratis::where('compagnie_id', session('loginId'))->first();

        if ($reponse) {

            $etat = $reponse->etat;
            $valeur = 1;


            if ($etat == 1) {
                $valeur = 0;
            }
            try {
                $reponse->update([
                    'etat' => $valeur,
                ]);

                return response()->json(['statut' => $reponse->etat], 200); // Réponse JSON

            } catch (\Exception $e) {
                // Gérer l'exception si la mise à jour échoue
                return response()->json(['statut' => $reponse->etat], 200); // Réponse JSON
            }
        } else {
            $reponse = $this->store();
            if ($reponse) {
                return response()->json(['statut' => $reponse->etat], 200);
            } else {
                return response()->json(['statut' => 0], 200);
            }
        }
    }
    public function updatePrixMaryajGratis(Request $request)
    {
        $request->montant;
        try {
            // Récupérer le modèle existant que vous souhaitez mettre à jour
            $maryajIsset = maryajgratis::where('compagnie_id', session('loginId'))
                ->first();

            if ($maryajIsset) {
                // Mettre à jour les champs nécessaires
                $maryajIsset->update([
                    'prix' => $request->montant,
                ]);

                notify()->success('Montant ajoute: ' . $request->montant);
                return redirect()->back();
            } else {
                notify()->error('Gen yon pwoblem kontakte ekip teknik');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            notify()->error('Gen yon pwoblem kontakte ekip teknik');
            return redirect()->back();
        }
    }
    public function create_config()
    {

        if (Session('loginId')) {

            $suppression = DB::table('ticket_suppression')->where([
                ['compagnie_id', '=', Session('loginId')]
            ])->first();
            if (!$suppression) {
                //insert
                $suppression = DB::table('ticket_suppression')->insert([
                    'compagnie_id' => Session('loginId')
                ]);
                //get
                $suppression = DB::table('ticket_suppression')->where([
                    ['compagnie_id', '=', Session('loginId')]
                ])->first();
            }
            return view('parametre.lotconfig', ['suppression' => $suppression]);
        } else {
            return view('login');
        }
    }
    public function update_delai(Request $request)
    {

        if (Session('loginId')) {
            $validator = $request->validate([
                'time' => 'required',
            ]);
            try {

                if (!empty($request->input('active') == '1')) {
                    $active = 1;
                } else {
                    $active = 0;
                }
                $suppression = DB::table('ticket_suppression')->where([
                    ['compagnie_id', '=', Session('loginId')]
                ])->update([
                    'is_active' => $active,
                    'delai' => $request->input('time'),
                    'updated_at' => Carbon::now()


                ]);
                notify()->success('chanjman fet');
                return back();
            } catch (\Throwable $th) {
                //throw $th;
            }
        } else {
            return view('login');
        }
    }
    public function ajistelo()
    {
        if (Session('loginId')) {
            $data = RulesOne::where('compagnie_id', session('loginId'))->first();
            return view('parametre/ajisteprixlo', compact('data'));
        } else {
            return view('login');
        }
    }

    public function storelopri(Request $request)
    {
        if ($request->montant == 1) {
            $montant = 50;
        } elseif ($request->montant == 2) {
            $montant = 60;
        }
        try {
            $reponse = RulesOne::where('compagnie_id', session('loginId'))->first();
            $reponse->update([
                'prix' => $montant,
            ]);

            notify()->success('Pri pwemye lo ajiste a: ' . $montant);
            return redirect()->back();
        } catch (\Exception $e) {
            notify()->error('Gen yon pwoblem kontakte ekip teknik');
            return redirect()->back();
        }
    }
}
