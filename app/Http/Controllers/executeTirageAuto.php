<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\ExecutionTirage;
use App\Models\company;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\tirage_record;
use Illuminate\Support\Carbon;
use App\Models\BoulGagnant;
use App\Models\participation;
use App\Models\TicketVendu;
use App\Models\ticket_code;

class executeTirageAuto extends Controller
{


    public function switchActon(Request $request){
             if($request->action=='update'){
                $this->executeTirageAuto($request);
             }elseif($request->action=='add'){
                $this->executeTirageAuto($request);
             }
    }
    public function  executeTirageAuto($request)
    {


        $tirageId = $request->input('tirageid');
        $date = $request->input('date');
        $unchiffre = $request->input('unchiffre');
        $premierchiffre = $request->input('premierchiffre');
        $secondchiffre = $request->input('secondchiffre');
        $troisiemechiffre = $request->input('troisiemechiffre');

        $erreurs = $this->validerEntrees($tirageId, $unchiffre, $premierchiffre, $secondchiffre, $troisiemechiffre);

        if ($erreurs) {
            $messagesErreur = implode(', ', $erreurs);
            notify()->error($messagesErreur);
            return redirect()->back();
        }
        
         
        //recuperation compagnie qui a service =1 et autotirage =1
        $compagnies = company::where('autoTirage', 1)->where('service', 1)->get();
        $date = date('Y-m-d', strtotime($request->date));
        $tirageid = $request->tirageid;
        $var = 0;
        //verifier heure tirage if >heure 
        $this->verificationHour($tirageid, $date);
        

        foreach ($compagnies as $compagnie) {
            
            //verifier etat service tirageRecord,if have vendeur ,  pour eviter toute erreur.
            if ($this->verificationService($compagnie->id, $tirageid) == true) {
                $idtrecord= DB::table('tirage_record')->where('compagnie_id', $compagnie->id)->where('tirage_id', $tirageid)->value('id');

                //verification si lo existe deja
                if ($this->ifExisteLo($compagnie->id, $idtrecord, $date) == false) {
                    
                    //verification si tout les services sont operationnel pour le compagnie en question.
                    if ($compagnie->service == 1) {

                     
                         //renitialiser fiche et boulgagnant if $request->action=='update' modification boul gagnant.
                        if($request->action=='update'){
                            $this->rentier($date,$idtrecord,$compagnie->id);
                            $this->supprimerBoulGagnant($compagnie->id, $idtrecord, $date);

                        }


                        //sauvegarde boulgagant.
                        $reponseStore = $this->store($compagnie->id, $idtrecord, $date, $unchiffre, $premierchiffre, $secondchiffre, $troisiemechiffre);
                        //signer participation
                        $this->participation($compagnie->id, $tirageid, $date);
                        //lancement du job pour le compagnie en question.
                        if ($reponseStore) {
                            dispatch(new ExecutionTirage($idtrecord, $compagnie->id, $date, session('id')));
                        }
                    }
                } else {
                    $var = $var + 1;
                }
            } else {
                $var = $var + 1;
            }
        }

        if ($var == 0) {
            notify()->success('Ajoute Lo effectuer avec succes :Aucun erreur' . $var);
        } else {
            notify()->info('Action echouer:,  Erreur trouver' . $var);
        }

        return redirect()->route('monitoring');
    }

    public function store($compagnieId, $tirageId, $date, $unchiffre, $premierchiffre, $secondchiffre, $troisiemechiffre)
    {

        $formattedDate = $date; //Carbon::createFromFormat('m-d-Y', $date)->format('Y-m-d');
        try {

            $reponseadd = BoulGagnant::create([
                'tirage_id' => $tirageId,
                'compagnie_id' => $compagnieId,
                'unchiffre' => $unchiffre,
                'premierchiffre' => $premierchiffre,
                'secondchiffre' => $secondchiffre,
                'troisiemechiffre' => $troisiemechiffre,
                'etat' => 'true',
                'created_' => $formattedDate
            ]);
            if ($reponseadd) {
                return true;
            }
        } catch (\Exception $e) {
            // Gérer l'exception si la création échoue
            return false;
        }
    }


    function ifExisteLo($compagnieId, $id, $date)
    {
        $exist = BoulGagnant::where('compagnie_id', $compagnieId)->where('tirage_id', $id)->where('created_', $date)->get();
        if ($exist->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function validerEntrees($tirageId, $unchiffre, $premierchiffre, $secondchiffre, $troisiemechiffre)


    {


        $premierchiffre = (string) $premierchiffre;
        $secondchiffre = (string) $secondchiffre;
        $troisiemechiffre = (string) $troisiemechiffre;

        $validator = Validator::make(
            [
                'tirage' => $tirageId,
                'unchiffre' => $unchiffre,
                'premierchiffre' => $premierchiffre,
                'secondchiffre' => $secondchiffre,
                'troisiemechiffre' => $troisiemechiffre,
            ],
            [
                'tirage' => 'required|integer',
                'unchiffre' => 'required|integer|digits:1',
                'premierchiffre' => 'required|string|size:2',
                'secondchiffre' => 'required|string|size:2',
                'troisiemechiffre' => 'required|string|size:2',
            ],
            [
                'unchiffre.digits' => 'Yon chif dwe yon sel chif.',
                'premierchiffre.digits' => 'Pwemye lot dwe 2chif',
                'secondchiffre.digits' => 'Dezyem lo dwe 2 chif.',
                'troisiemechiffre.digits' => 'twazyem lot dwe 2 chif',
            ]
        );

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        return null; // Aucune erreur de validation
    }

    function verificationService($compagnieId, $tirage)
    {
        $existeUser = DB::table('users')->where('compagnie_id', $compagnieId)->exists();
        $existeTirage = DB::table('tirage_record')->where('compagnie_id', $compagnieId)->where('tirage_id', $tirage)->exists();

        if ($existeUser && $existeTirage) {
            return true;
        } else {
            return false;
        }
    }


    function verificationHour($tirageId, $date)
    {
        $heureTirage = DB::table('tirage')->where('id', $tirageId)->value('hour_tirer');


        $heureServeur = Carbon::now()->format('H:i:s');



        if (Carbon::parse($heureServeur)->gte(Carbon::parse($heureTirage)) || $date < Carbon::now()->format('Y-m-d')) {
        } else {
            // Le tirage n'a pas encore commencé
            notify()->info('lo an poko ka mete. tiraj la ap femen a:' . $heureTirage);
            return redirect()->back();
        }
    }

    public function participation($compagnieId, $tirage, $date)
    {
        $resultat = participation::create([
            'compagnie_id'=>$compagnieId,
            'tirage_id'=>$tirage, 
            'date_'=>$date,
        ]);
    }






    public function rentier($date,$tirageName,$compagnie){
        // Récupérer les codes fiches 
        $numero = ticket_code::where('compagnie_id', $compagnie)
                            ->whereDate('created_at', $date)
                            ->pluck('code')
                            ->toArray();
               
        if ($numero) {
            // Récupérer les tickets vendus
            $listefiche = TicketVendu::whereIn('ticket_code_id', $numero)
                                     ->where('tirage_record_id', $tirageName)
                                     ->where('is_win','1')
                                     ->get();
                                     
                   // dd($listefiche,$date,$tirageName,$numero);                
            if ($listefiche->count() > 0) {
                // Parcourir chaque fiche et mettre à jour les champs
                foreach ($listefiche as $fiche) {
                    $fiche->update([
                        'is_win' => '0', // Mettre à jour is_win à 0
                        'winning' => 0,
                        'is_calculated' =>0,
                    ]);
                }
                return true; // Opération réussie
            } else {
                return true; // Pas de fiche à mettre à jour
            }
        } else {
            return true; // Pas de codes de fiche trouvés
        }
}

function supprimerBoulGagnant($compagnieId, $tirageId, $date)
{
    // Rechercher l'élément correspondant
    $boulGagnant = BoulGagnant::where('compagnie_id', $compagnieId)
                              ->where('tirage_id', $tirageId)
                              ->where('created_', $date)
                              ->first();

    // Vérifier si l'élément existe
    if ($boulGagnant) {
        // Supprimer l'élément
        return $boulGagnant->delete();
    }

    // Retourner False si l'élément n'existe pas
    return false;
}


}
