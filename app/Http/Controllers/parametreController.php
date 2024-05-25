<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\maryajgratis;
use App\Models\RulesOne;
use App\Models\limitprixachat;
use App\Models\limitprixboul;
use App\Models\tirage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use App\Models\tirage_record;

class parametreController extends Controller
{

    public  function indexmaryaj()
    {
        $data = maryajgratis::where('compagnie_id', session('loginId'))->first();
        return view('parametre.maryajGratis', compact('data'));
    }

    public function limitprixview(){
      
      
        $limitprix= limitprixachat::where('compagnie_id', session('loginId'))->first();
        if(!$limitprix){
            $query = limitprixachat::insert([
                 'compagnie_id'=> Session('loginId'),
            ]);
            $limitprix= limitprixachat::where('compagnie_id', session('loginId'))->first();

        }
        $listetirage=tirage_record::where('compagnie_id',session('loginId'))->get();
        $limitprixboul=limitprixboul::where('compagnie_id',session('loginId'))->orderBy('tirage_record') // Triez par tirage_record
        ->get();
      

        $listjwet=DB::table('listejwet')->get();
        return view('parametre.limitPrixAchat', compact('limitprix','listetirage','limitprixboul','listjwet'));
    }

    public function ajoutlimitprixboulView(){
        $list=tirage_record::where('compagnie_id',session('loginId'))->get();
        $listjwet=DB::table('listejwet')->get();
        return view('parametre.ajouterLimitPrixBoul', compact('list','listjwet'));
    }

    public function saveprixlimit(Request $request){
                $reponse=$this->regles($request);
                $nametirage=tirage_record::where('compagnie_id',session('loginId'))->where('id',$request->tirage)->value('name');
                $nameAssociatedWithType = DB::table('listejwet')->where('id', $request->type)->value('name');
                //verification si opsyon koresponn ak boul
                if($reponse==false){
                    notify()->error('verifye ke boul la korespon ak opsyon an, oubyen li ajoute deja');
                return redirect()->back();
                }else{
                       //verifier si limit 10 boul lan rive 
                    $count = DB::table('limit_prix_boul')->where('opsyon',$nameAssociatedWithType )->where('compagnie_id',session('loginId'))->count();
                    if($count==10){
                        notify()->error('Ou rive nan limit 10 boul la deja pou option sa');
                        return redirect()->back();
                    }
                       //verifier si boul sa existe deja pou 
                    $boule=DB::table('limit_prix_boul')->where('opsyon',$nameAssociatedWithType)->where('compagnie_id',session('loginId'))->where('tirage_record',$request->tirage)->where('boul',$request->chiffre)->first();
                     
                    if($boule){
                        notify()->error('boul sa ekziste deja pou Tiraj sa e opsyon sa sa');
                        return redirect()->back();
                    }
                    
                    //$this->vericationMaryajDouble($request,$request->type,$nameAssociatedWithType);
                    
                        $reponse = limitprixboul::create([
                            'tirage_record' => $request->tirage,
                            'compagnie_id' => session('loginId'),
                            'type' => $nametirage,
                            'opsyon' => $nameAssociatedWithType,
                            'boul' => $request->chiffre,
                            'montant' => $request->montant,
                        ]);
                    
                    

                    if($reponse){
                      
                notify()->success('Ajoute avek sikse');
                return redirect()->route('limitprix');
                    }
                }
    }
   //fonction de suppresion dans la table limitprixboul
    public function modifierLimitePrix(Request $request){
        $reponse=limitprixboul::where('id',$request->id)->where('compagnie_id',session('loginId'))->delete();
        if($reponse){
            notify()->success('Siprime avek sikse');
            return redirect()->route('limitprix');
        }else{
            notify()->error('Sipresyon pa posib, sil pesiste kontakte ekip teknik');
            return redirect()->back();
        }
    }

    public function vericationMaryajDouble($request,$type,$nameAssociatedWithType){
        $chiffre=$request->chiffre;
        $deuxPremiersChiffres = substr($chiffre, 0, 2);
        $deuxDerniersChiffres = substr($chiffre, -2);
        $chiffresFinal = $deuxDerniersChiffres.$deuxPremiersChiffres;
        
        $boule=DB::table('limit_prix_boul')->where('opsyon',$nameAssociatedWithType)->where('compagnie_id',session('loginId'))->where('tirage_record',$request->tirage)->where('boul',$chiffresFinal)->first();
       
        if($boule){
           return true;
        }else{
            return false;
        }        
    }
    public function regles($request){
        $type = request()->input('type');
        $value = $request->chiffre;
        $nameAssociatedWithType = DB::table('listejwet')->where('id', $type)->value('name');
        
        switch ($nameAssociatedWithType) {
            case 'Bolet':
                $mg=strlen($value);
               
                if($mg==2){
                return true;
            }else{
                return false;
            }
            case 'Maryaj':
                
                $mg=strlen($value);
               
                if($mg==4){
                    $reponse=$this->vericationMaryajDouble($request,$type,$nameAssociatedWithType);
                    if($reponse==true){
                        
                        return false;
                    }else{
                    return true;
                }
                }else{
                    return false;
                }
            case 'Loto3':
                $mg=strlen($value);
               
                if($mg==3){
                    return true;
                }else{
                    return false;
                }
            case 'Loto4':
                $mg=strlen($value);
               
                if($mg==4){
                    return true;
                }else{
                    return false;
                }
            case 'Loto5':
                $mg=strlen($value);
               
                if($mg==5){
                    return true;
                }else{
                    return false;
                }
            default:
                return false; // Si le nom n'est pas reconnu, la validation échoue
        }

    }
    public function limitprixstore(Request $request){
        if(!Empty($request->active) && $request->prix>0 && !Empty($request->prix)){
         $active=true;
        }else{
            $active=false;
        }
        
        try {
            $key = $request->id . 'etat';
            // Recherche de la ligne correspondante avec 'compagnie_id'
            $limitprix = limitprixachat::where('compagnie_id', session('loginId'))->first();
        
            if ($limitprix) {
                // Mise à jour de la ligne existante
                $limitprix->update([
                 $request->id => $request->prix,
                 $key=>$active,
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
        
        try {
            // Récupérer le modèle existant que vous souhaitez mettre à jour
            $maryajIsset = maryajgratis::where('compagnie_id', session('loginId'))
                ->first();

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


    //gestion plan
    public function viewinfo(Request $request){
        $data=DB::table('companies')->where('id',session('loginId'))->first();
        $nombre=$this->getDaysRemaining($data->dateplan,$data->dateexpiration);
        return view('plan', compact('data','nombre'));
    }

    function getDaysRemaining($dateplan,$datefin)
    {
        
        $startDate = Carbon::parse($dateplan);
        $endDate = Carbon::parse($datefin);
        $currentDate = Carbon::now();

        // Vérifier si l'abonnement est encore valide
        if ($currentDate->between($startDate, $endDate)) {
            $daysRemaining = $currentDate->diffInDays($endDate);
            return  $daysRemaining;
        } else {
            return $d= 0;
            
        }
    }

     //update is_generale
     public function update_general(Request $request){
        $data=limitprixboul::where('id',$request->id)->where('compagnie_id',session('loginId'))->first();
         // dd($request->id);
        if($data){
            $var=0;
            $is_generale=$data->is_general;

            if($is_generale==0){
               $var=1;
            }
           $reponse=$data->update([
                  'is_general'=>$var,  
           ]);
           if($reponse){
            notify()->success('Modifikasyon sikse');
            return redirect()->back();
           }else{
            notify()->error('Erreur');
            return redirect()->back();
           }
        }

     }

}
