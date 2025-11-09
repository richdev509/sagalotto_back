<?php

namespace App\Http\Controllers;

use App\Models\branch;
use App\Models\company;
use Illuminate\Http\Request;
use App\Models\maryajgratis;
use App\Models\RulesOne;
use App\Models\limitprixachat;
use App\Models\limitprixboul;
use App\Models\ticket_code;
use App\Models\tirage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use App\Models\tirage_record;
use App\Models\limit_auto;
use App\Models\rules_vendeur;
use App\Models\seting;

class parametreController extends Controller
{

    public  function indexmaryaj()
    {
        $data = maryajgratis::where([
            ['compagnie_id', session('loginId')],
        ])->first();
        $branch = branch::where('compagnie_id', session('loginId'))->get();
        return view('parametre.maryajGratis', compact('data', 'branch'));
    }

    public function limitprixview()
    {


        $limitprix = limitprixachat::where('compagnie_id', session('loginId'))->first();
        if (!$limitprix) {
            $query = limitprixachat::insert([
                'compagnie_id' => Session('loginId'),
            ]);
            $limitprix = limitprixachat::where('compagnie_id', session('loginId'))->first();
        }
        $listetirage = tirage_record::where('compagnie_id', session('loginId'))->get();
        $limitprixboul = limitprixboul::where('compagnie_id', session('loginId'))->orderBy('tirage_record') // Triez par tirage_record
            ->get();

        $list = [];


        foreach ($limitprixboul as $single_boul) {
            $amount = limit_auto::where([
                ['compagnie_id', '=', Session('loginId')],
                ['tirage', '=', $single_boul->type],
                ['boule', '=', $single_boul->boul],
                ['type', '=', lcfirst($single_boul->opsyon)],
            ])->whereDate('created_at', '=', Carbon::today())
                ->sum('amount');


            $list[] = [
                'id' => $single_boul->id,
                'type' => $single_boul->type,
                'boul' => $single_boul->boul,

                'opsyon' => $single_boul->opsyon,
                'montant_limit' => $single_boul->montant,
                'montant_play' => $amount,
                'created_at' => $single_boul->created_at


            ];
        }


        $listjwet = DB::table('listejwet')->get();
        return view('parametre.limitPrixAchat', compact('limitprix', 'listetirage', 'list', 'listjwet'));
    }

    public function ajoutlimitprixboulView()
    {
        $list = tirage_record::where('compagnie_id', session('loginId'))->get();
        $listjwet = DB::table('listejwet')->get();
        return view('parametre.ajouterLimitPrixBoul', compact('list', 'listjwet'));
    }

    public function saveprixlimit(Request $request)
    {
        $reponse = $this->regles($request);

        //verification si opsyon koresponn ak boul
        if ($reponse == false) {
            notify()->error('verifye ke boul la korespon ak opsyon an, oubyen li ajoute deja');
            return redirect()->back();
        } else {
            //insert boul for each tirage
            foreach ($request->input('tirage') as $tirage_input) {
                if ($request->input('type') == 'paire') {
                    $paire = array();
                    $paire = ['00', '11', '22', '33', '44', '55', '66', '77', '88', '99'];
                    foreach ($paire as $p) {
                        $nametirage = tirage_record::where('compagnie_id', session('loginId'))->where('id', $tirage_input)->value('name');
                        $boule = limitprixboul::where('opsyon', 'Bolet')->where('compagnie_id', session('loginId'))->where('tirage_record', $tirage_input)->where('boul', $p)->first();

                        if ($boule) {
                            $boule->delete();
                        }
                        $reponse = limitprixboul::create([
                            'tirage_record' => $tirage_input,
                            'compagnie_id' => session('loginId'),
                            'type' => $nametirage,
                            'opsyon' => 'Bolet',
                            'boul' => $p,
                            'montant' => $request->montant,
                            'montant1' => $request->montant,

                        ]);
                    }
                } else if ($request->input('type') == 'grap') {
                    $grap = array();
                    $grap = ['000', '111', '222', '333', '444', '555', '666', '777', '888', '999'];
                    foreach ($grap as $p) {
                        $nametirage = tirage_record::where('compagnie_id', session('loginId'))->where('id', $tirage_input)->value('name');
                        $boule = limitprixboul::where('opsyon', 'Loto3')->where('compagnie_id', session('loginId'))->where('tirage_record', $tirage_input)->where('boul', $p)->first();

                        if ($boule) {
                            $boule->delete();
                        }
                        $reponse = limitprixboul::create([
                            'tirage_record' => $tirage_input,
                            'compagnie_id' => session('loginId'),
                            'type' => $nametirage,
                            'opsyon' => 'Loto3',
                            'boul' => $p,
                            'montant' => $request->montant,
                            'montant1' => $request->montant,

                        ]);
                    }
                } else {
                    $nametirage = tirage_record::where('compagnie_id', session('loginId'))->where('id', $tirage_input)->value('name');
                    $nameAssociatedWithType = DB::table('listejwet')->where('id', $request->type)->value('name');
                    $count = DB::table('limit_prix_boul')->where('opsyon', $nameAssociatedWithType)->where('compagnie_id', session('loginId'))->count();
                    if ($count >= 500) {
                        notify()->error('Ou rive nan limit 500 boul la deja pou option sa');
                        return redirect()->back();
                    }
                    //verifier si boul sa existe deja pou 
                    $boule = limitprixboul::where('opsyon', $nameAssociatedWithType)->where('compagnie_id', session('loginId'))->where('tirage_record', $tirage_input)->where('boul', $request->chiffre)->first();

                    if ($boule) {
                        $boule->delete();
                    }

                    //$this->vericationMaryajDouble($request,$request->type,$nameAssociatedWithType);
                    if (isset($request->isgeneral) && $request->isgeneral == 45) {
                        $reponse = limitprixboul::create([
                            'tirage_record' => $tirage_input,
                            'compagnie_id' => session('loginId'),
                            'type' => $nametirage,
                            'opsyon' => $nameAssociatedWithType,
                            'boul' => $request->chiffre,
                            'montant' => 0,
                            'is_general' => 1,
                        ]);
                    } else {
                        $reponse = limitprixboul::create([
                            'tirage_record' => $tirage_input,
                            'compagnie_id' => session('loginId'),
                            'type' => $nametirage,
                            'opsyon' => $nameAssociatedWithType,
                            'boul' => $request->chiffre,
                            'montant' => $request->montant,
                            'montant1' => $request->montant,

                        ]);
                    }
                }
            }
            //verifier si limit 10 boul lan rive 


            if ($reponse) {
                if (isset($request->isgeneral) && $request->isgeneral == 45) {
                    notify()->success('Bloke success');
                    return redirect()->back();
                } else {
                    notify()->success('Ajouter success');
                    return redirect()->route('limitprix');
                }
            }
        }
    }
    //fonction de suppresion dans la table limitprixboul
    public function modifierLimitePrix(Request $request)
    {
        $reponse = limitprixboul::where('id', $request->id)->where('compagnie_id', session('loginId'))->delete();
        if ($reponse) {
            notify()->success('Siprime avek sikse');
            return redirect()->route('limitprix');
        } else {
            notify()->error('Sipresyon pa posib, sil pesiste kontakte ekip teknik');
            return redirect()->back();
        }
    }

    public function vericationMaryajDouble($request, $type, $nameAssociatedWithType)
    {
        $chiffre = $request->chiffre;
        $deuxPremiersChiffres = substr($chiffre, 0, 2);
        $deuxDerniersChiffres = substr($chiffre, -2);
        $chiffresFinal = $deuxDerniersChiffres . $deuxPremiersChiffres;

        $boule = DB::table('limit_prix_boul')->where('opsyon', $nameAssociatedWithType)->where('compagnie_id', session('loginId'))->where('tirage_record', $request->tirage)->where('boul', $chiffresFinal)->first();

        if ($boule) {
            return true;
        } else {
            return false;
        }
    }
    public function regles($request)
    {
        $type = request()->input('type');
        $value = $request->chiffre;
        if ($type == 'paire') {
            $nameAssociatedWithType = 'paire';
        } else if ($type == 'grap') {
            $nameAssociatedWithType = 'grap';
        } else {
            $nameAssociatedWithType = DB::table('listejwet')->where('id', $type)->value('name');
        }

        switch ($nameAssociatedWithType) {
            case 'paire':

                return true;

            case 'grap':
                return true;
            case 'Bolet':
                $mg = strlen($value);

                if ($mg == 2) {
                    return true;
                } else {
                    return false;
                }
            case 'Maryaj':

                $mg = strlen($value);

                if ($mg == 4) {
                    $reponse = $this->vericationMaryajDouble($request, $type, $nameAssociatedWithType);
                    if ($reponse == true) {

                        return false;
                    } else {
                        return true;
                    }
                } else {
                    return false;
                }
            case 'Loto3':
                $mg = strlen($value);

                if ($mg == 3) {
                    return true;
                } else {
                    return false;
                }
            case 'Loto4':
                $mg = strlen($value);

                if ($mg == 4) {
                    return true;
                } else {
                    return false;
                }
            case 'Loto5':
                $mg = strlen($value);

                if ($mg == 5) {
                    return true;
                } else {
                    return false;
                }
            default:
                return false; // Si le nom n'est pas reconnu, la validation échoue
        }
    }
    public function limitprixstore(Request $request)
    {
        if (!empty($request->active)) {
            $active = true;
        } else {
            $active = false;
        }

        try {
            $key = $request->id . 'etat';
            // Recherche de la ligne correspondante avec 'compagnie_id'
            $limitprix = limitprixachat::where('compagnie_id', session('loginId'))->first();

            if ($limitprix) {
                // Mise à jour de la ligne existante
                $limitprix->update([
                    $request->id => $request->prix,

                    $key => $active,
                ]);
            } else {
                // Création d'une nouvelle ligne
                $limitprix = Limitprixachat::create([
                    'compagnie_id' => session('loginId'),
                    $request->id => $request->prix
                ]);
            }
            notify()->success('Modifikasyon sikse');
            return redirect()->route('limitprix');
            // Réponse ou traitement supplémentaire
        } catch (\Exception $e) {
            notify()->error('Gen yon pwoblem kontakte ekip teknik');
            return redirect()->back();
        }
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
        $validator = $request->validate([
            'branch' => 'required',
        ]);
        $statut = $request->status;
        $reponse = maryajgratis::where([
            ['compagnie_id', session('loginId')],
            ['branch_id', $request->branch]

        ])->first();

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

        try {
            // Récupérer le modèle existant que vous souhaitez mettre à jour
            $maryajIsset = maryajgratis::where([
                ['compagnie_id', session('loginId')],
                ['branch_id', $request->input('branch')]
            ])->first();

            if ($maryajIsset) {

                // Mettre à jour les champs nécessaires
                $maryajIsset->prix = $request->input('montant');
                $maryajIsset->q_inter_1 = $request->input('q_inter_1');
                $maryajIsset->min_inter_1 = $request->input('min_inter_1');
                $maryajIsset->max_inter_1 = $request->input('max_inter_1');
                $maryajIsset->q_inter_2 = $request->input('q_inter_2');
                $maryajIsset->min_inter_2 = $request->input('min_inter_2');
                $maryajIsset->max_inter_2 = $request->input('max_inter_2');
                $maryajIsset->q_inter_3 = $request->input('q_inter_3');
                $maryajIsset->min_inter_3 = $request->input('min_inter_3');
                $maryajIsset->max_inter_3 = $request->input('max_inter_3');
                $maryajIsset->q_inter_4 = $request->input('q_inter_4');
                $maryajIsset->min_inter_4 = $request->input('min_inter_4');
                $maryajIsset->max_inter_4 = $request->input('max_inter_4');
                $maryajIsset->q_inter_5 = $request->input('q_inter_5');
                $maryajIsset->min_inter_5 = $request->input('min_inter_5');
                $maryajIsset->max_inter_5 = $request->input('max_inter_5');
                $maryajIsset->q_inter_6 = $request->input('q_inter_6');
                $maryajIsset->min_inter_6 = $request->input('min_inter_6');
                $maryajIsset->max_inter_6 = $request->input('max_inter_6');
                $maryajIsset->save();

                notify()->success('tout paramet yo byen mofifye');
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
    public function config_fich()
    {

        if (Session('loginId')) {

            $fich_config = DB::table('setings')->where([
                ['compagnie_id', '=', Session('loginId')]
            ])->first();
            if (!$fich_config) {
                //insert
                $fich_config = DB::table('setings')->insert([
                    'compagnie_id' => Session('loginId')
                ]);
                //get
                $fich_config = DB::table('setings')->where([
                    ['compagnie_id', '=', Session('loginId')]
                ])->first();
            }
            return view('fich', ['fich' => $fich_config]);
        } else {
            return view('login');
        }
    }

    public function config_fichUpdate(Request $request)
    {


        try {
            // Récupérer le modèle existant que vous souhaitez mettre à jour
            $settingIsset = seting::where([
                ['compagnie_id', session('loginId')],
            ])->first();
            if ($settingIsset) {

                // Mettre à jour les champs nécessaires
                $settingIsset->qt_bolet = $request->input('qt_bolet') ?: 100;
                $settingIsset->qt_maryaj = $request->input('qt_maryaj') ?: 250;
                $settingIsset->qt_loto3 = $request->input('qt_loto3') ?: 100;
                $settingIsset->qt_loto4 = $request->input('qt_loto4') ?: 100;
                $settingIsset->qt_loto5 = $request->input('qt_loto5') ?: 100;
                $settingIsset->show_logo = $request->input('show_logo') ?: 0;
                $settingIsset->show_mariage_price = $request->input('show_mariage_price') ?: 0;
                $settingIsset->show_name = $request->input('show_name') ?: 0;
                $settingIsset->show_phone = $request->input('show_phone') ?: 0;
                $settingIsset->show_address = $request->input('show_address') ?: 0;
                $settingIsset->show_footer = $request->input('show_footer') ?: 0;

                $settingIsset->save();

                notify()->success('tout paramet yo byen mofifye');
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
            //prix premye lo
            $data = RulesOne::where('compagnie_id', session('loginId'))->first();
            //service info
            $service = company::where('id', session('loginId'))->first();
            //get all branch
            $branch = branch::where([
                ['compagnie_id', '=', Session('loginId')],
                ['is_delete', '=', 0]
            ])->get();
            return view('parametre/ajisteprixlo', ['data' => $data, 'service' => $service, 'branch' => $branch]);
        } else {
            return view('login');
        }
    }
    public function getPrixLo(Request $request)
    {
        if (Session('loginId')) {
            //prix premye lo
            $data = RulesOne::where([
                ['compagnie_id', '=', session('loginId')],
                ['branch_id', '=', $request->id]
            ])->first();
            if (!$data) {
                RulesOne::insert([
                    'compagnie_id' => Session('loginId'),
                    'branch_id' => $request->id,
                    'prix' => 50


                ]);
                //get
                $data = RulesOne::where([
                    ['compagnie_id', '=', Session('loginId')],
                    ['branch_id', '=', $request->id]
                ])->first();
            }
            //service info

            return response()->json([
                'status' => 'true',
                'data' => $data
            ]);
        } else {
            return view('login');
        }
    }
    public function getPrixMaryaj(Request $request)
    {
        if (Session('loginId')) {
            //prix premye lo
            $data = maryajgratis::where([
                ['compagnie_id', '=', session('loginId')],
                ['branch_id', '=', $request->id]
            ])->first();
            if (!$data) {
                maryajgratis::insert([
                    'compagnie_id' => Session('loginId'),
                    'branch_id' => $request->id,
                    'prix' => 0


                ]);
                //get
                $data = maryajgratis::where([
                    ['compagnie_id', '=', Session('loginId')],
                    ['branch_id', '=', $request->id]
                ])->first();
            }
            //service info

            return response()->json([
                'status' => 'true',
                'data' => $data
            ]);
        } else {
            return view('login');
        }
    }



    public function storelopri(Request $request)
    {

        try {

            $responce = RulesOne::where([
                ['compagnie_id', session('loginId')],
                ['branch_id', $request->branch],

            ])->first();

            $prixGabel1 = $request->input('prix_gabel1');
            if (!isset($prixGabel1)) {
                $request->merge([
                    'prix_gabel1' => 20,
                    'prix_gabel2' => 10,
                    'gabel_statut' => 0,
                ]);
            }
            $responce->update([
                'prix' => $request->input('prix_first'),
                'prix_second' => $request->input('prix_second'),
                'prix_third' => $request->input('prix_third'),
                'prix_maryaj' => $request->input('prix_maryaj'),
                'prix_loto3' => $request->input('prix_loto3'),
                'prix_loto4' => $request->input('prix_loto4'),
                'prix_loto5' => $request->input('prix_loto5'),
                'prix_gabel1' => $request->input('prix_gabel1'),
                'prix_gabel2' => $request->input('prix_gabel2'),
                'gabel_statut' => 1,


            ]);

            //store service auto tirage
            if (!empty($request->input('tirage_auto')) == '1') {
                $service = company::where('id', session('loginId'))->update([
                    'autoTirage' => 1,
                    'service' => 1,

                ]);
            } else {
                $service = company::where('id', session('loginId'))->update([
                    'autoTirage' => 0,
                    'service' => 0,

                ]);
            }

            notify()->success('Modifikasyon fet ak sikse');
            return redirect()->back();
        } catch (\Exception $e) {
            notify()->error('Gen yon pwoblem kontakte ekip teknik', $e);
            return redirect()->back();
        }
    }
    public function update_prilo_vendeur(Request $request)
    {

        try {

            if(isset($request->id)){
                $rules_vendeur = rules_vendeur::where([
                    ['compagnie_id', session('loginId')],
                    ['id', $request->id],
                ])->first();
                if($rules_vendeur){
                    $rules_vendeur->update([
                        'prix' => $request->input('prix'),
                        'prix_second' => $request->input('prix_second'),
                        'prix_third' => $request->input('prix_third'),
                        'prix_maryaj' => $request->input('prix_maryaj'),
                        'prix_maryaj_gratis' => $request->input('prix_maryaj_gratis'),
                        'prix_loto3' => $request->input('prix_loto3'),
                        'prix_loto4' => $request->input('prix_loto4'),
                        'prix_loto5' => $request->input('prix_loto5'),
                        'prix_gabel1' => 20,
                        'prix_gabel2' => 10,
                        'gabel_statut' => 0,
                    ]);
                    notify()->success('Modifikasyon fet ak sikse');
                    return redirect()->back();


                }else{
                    notify()->error('Aucun enregistreman trouve');
                    return redirect()->back();
                }

            }else{
                $rules_vendeur = new rules_vendeur();
                $rules_vendeur->compagnie_id = session('loginId');
                $rules_vendeur->user_id = $request->input('user_id');
                $rules_vendeur->prix = $request->input('prix');
                $rules_vendeur->prix_second = $request->input('prix_second');
                $rules_vendeur->prix_third = $request->input('prix_third');
                $rules_vendeur->prix_maryaj = $request->input('prix_maryaj');
                $rules_vendeur->prix_maryaj_gratis = $request->input('prix_maryaj_gratis');
                $rules_vendeur->prix_loto3 = $request->input('prix_loto3');
                $rules_vendeur->prix_loto4 = $request->input('prix_loto4');
                $rules_vendeur->prix_loto5 = $request->input('prix_loto5');
                $rules_vendeur->prix_gabel1 = 20;
                $rules_vendeur->prix_gabel2 = 10;
                $rules_vendeur->gabel_statut = 0;
                $rules_vendeur->save();
                notify()->success('Enregistrement fet ak sikse');
                return redirect()->back();


            }
        } catch (\Exception $e) {
            notify()->error('Gen yon pwoblem kontakte ekip teknik', $e);
            return redirect()->back();
        }
    }
    public function deleteprilo_vendeur(Request $request)
    {
        try {
            $rules_vendeur = rules_vendeur::where([
                ['compagnie_id', session('loginId')],
                ['id', $request->id],
            ])->first();
            if ($rules_vendeur) {
                $rules_vendeur->delete();
                notify()->success('Enregistrement supprime avec succes');
                return redirect()->back();
            } else {
                notify()->error('Aucun enregistrement trouve');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            notify()->error('Gen yon pwoblem kontakte ekip teknik', $e);
            return redirect()->back();
        }

    }

    //gestion plan
    public function viewinfo(Request $request)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $data = company::find(session('loginId'));
            $data->address = $request->input('address');
            $data->phone = $request->input('phone');
            $data->save();
            notify()->success('Modification effectuée avec succès');
            return back();

        }else{
        $data = DB::table('companies')->where('id', session('loginId'))->first();
        $nombre = $this->getDaysRemaining(Carbon::now()->toDateString(), $data->dateexpiration);
        //active user that create ticket last 30 days
        $vendeur = ticket_code::where([
            ['created_at', '>=', Carbon::now()->subDays(5)],
            ['compagnie_id', '=', Session('loginId')]

        ])->distinct()
            ->pluck('user_id')
            ->count();

        return view('plan', compact('data', 'nombre', 'vendeur'));
        }
    }

    function getDaysRemaining($dateplan, $datefin)
    {

        $startDate = Carbon::parse($dateplan);
        $endDate = Carbon::parse($datefin);
        $currentDate = Carbon::now();

        // Vérifier si l'abonnement est encore valide
        if ($currentDate->between($startDate, $endDate)) {
            $daysRemaining = $currentDate->diffInDays($endDate);
            return  $daysRemaining;
        } else {
            return $d = 0;
        }
    }

    //update is_generale
    public function update_general(Request $request)
    {
        $data = limitprixboul::where('id', $request->id)->where('compagnie_id', session('loginId'))->first();
        // dd($request->id);
        if ($data) {
            $var = 0;
            $is_generale = $data->is_general;

            if ($is_generale == 0) {
                $var = 1;
            }
            $reponse = $data->update([
                'is_general' => $var,
            ]);
            if ($reponse) {
                notify()->success('Modifikasyon sikse');
                return redirect()->back();
            } else {
                notify()->error('Erreur');
                return redirect()->back();
            }
        }
    }
    //job
    public function updateBranch()
    {
        // Récupérer tous les IDs des compagnies


        dd('sikse');
    }
    public function deleteMultiple(Request $request)
    {
        $selectedIds = $request->input('selected_ids', []);

        if (empty($selectedIds)) {
            return back()->with('error', 'Pa gen okenn limit chwazi pou siprime.');
        }

        // Delete the selected records
        // Adjust this according to your model and database structure
        limitprixboul::whereIn('id', $selectedIds)->delete();

        return back()->with('success', 'Limit yo te siprime avèk siksè.');
    }
}
