<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\company;
use App\Models\user;
use App\Models\RulesOne;
use App\Models\RulesTwo;
use App\Models\BoulGagnant;
use App\Models\TicketVendu;
use App\Models\ticket_code;
use App\Models\tirage_record;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\maryajgratis;
use App\Jobs\ExecutionTirage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class executeTirageController extends Controller
{
    //
    public $totalGains=0;
    public $totalgains=[];
    public $totalMontantJouer=0;
    public $gagnantsData = [];
    public $gagnantsDataParCode = [];
    public $havegain=0;
    public $havegainmaryaj=0;
    public $havegainloto3=0;
    public $havegainloto4=0;
    public $havegainloto5=0;
    public $montantparid=[];
    public $havegainmaryajGratis=0;
    public $rules=null;
    public $rules2=null;
    public $i=1;
    public $compagnieId=null;
    public $gagnants=null;
    public $borletePrice=null;
    public $tirageName=null;
    public $lotoPrices=null;
    public $tirage=null;
    public $foundfiches=0;
    //debut fonction

    public function verification($tirage,$date){
            
            $tirageid=$tirage;
             $data=$this->execute($tirageid,$date);
             if($data==1){
                return $statut='1';
             }else{
                return $statut='-1';
             }
            
    }

    public function rentier($date,$tirageName){
       
         
            // Récupérer les codes fiches 
            $numero = ticket_code::where('compagnie_id', session('loginId'))
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


    public function rentierauto($compagnieId,$tirageName,$date){
       
         
        // Récupérer les codes fiches 
        $numero = ticket_code::where('compagnie_id', $compagnieId)
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

    public function execute($tirage,$date){

     $totalGains =0;
     $compagnieId =session('loginId'); 

     $this->borletePrice =RulesOne::where('compagnie_id', $compagnieId)->value('prix');
     $lotoNames = ['maryaj', 'loto3', 'loto4', 'loto5'];
     $this->lotoPrices = RulesTwo::whereIn('loto_name', $lotoNames)->pluck('prix', 'loto_name');
     $maryajgratis= maryajgratis::where('compagnie_id',$compagnieId)->where('etat',1)->value('prix');
     $tirageName = $tirage;
     $this->tirage=$tirage;
     // Récupération des numéros gagnants pour le tirage spécifique
     $formattedDate = $date;


     $this->gagnants = BoulGagnant::where('compagnie_id', $compagnieId)
          ->where('tirage_id', $tirageName)
          ->whereDate('created_', $formattedDate)
          ->first();
      

          
 $this->rules =RulesOne::where('compagnie_id', $compagnieId)->get();
 $this->rules2 =maryajgratis::where('compagnie_id', $compagnieId)->get();
 $this->i=1;



     //Recupere liste des codes vendu pour le jour en question.
     TicketVendu::select('ticket_vendus.id', 'ticket_vendus.tirage_code_id', 'ticket_vendus.tirage_record_id', 'ticket_vendus.boule')
    // Spécifiez les colonnes nécessaires
    ->whereHas('ticketCode', function ($query) use ($compagnieId, $formattedDate) {
        $query->where('compagnie_id', $compagnieId)
              ->whereDate('created_at', $formattedDate);
    })
    ->where('tirage_record_id', $tirageName)
    ->chunk(50, function ($fiches) {
        foreach ($fiches as $fiche) {
            $this->foundfiches=1;
            $ticketcode=$fiche->ticket_code_id;
            $numerobranch=ticket_code::where('compagnie_id', $this->compagnieId)
            ->where('code',$ticketcode)
            ->value('branch_id');
$borletePrice = 50;
// Trouver la règle correspondant au branch_id
$rule = $this->rules->firstWhere('branch_id', $numerobranch);
$rule2=$this->rules2->firstWhere('branch_id',$numerobranch);
// Vérifier si la règle a été trouvée
if ($rule) {
    // Obtenir le prix à partir de la règle
    $borletePrice = $rule->prix;
}
if($rule2){
    $maryajgratis=$rule2->prix;
} 

//dd($numerobranch);

$ficheData = json_decode($fiche->boule, true);
$ficheDatas=$ficheData;
 //recuperation de l'id du fiche
 $codeSpecifique=$fiche->ticket_code_id;
 //apel des function.
 
 $reponse=$this->bolete($this->gagnants,$ficheData,$borletePrice);
 
 $reponse= $this->maryaj($this->gagnants,$ficheData,$this->lotoPrices['maryaj']);

$this->loto3($this->gagnants,$ficheData,$this->lotoPrices['loto3']);

$this->loto4($this->gagnants,$ficheData,$this->lotoPrices['loto4']);

$this->loto5($this->gagnants,$ficheData,$this->lotoPrices['loto5']);

$this->mariagegratis($this->gagnants,$ficheData,$maryajgratis);


$this->totalgains[$this->i][]=$this->totalGains;
$this->totalgains[$this->i][]=$codeSpecifique;

 if($this->havegain==1 || $this->havegainmaryaj==1 || $this->havegainloto3==1 || $this->havegainloto4==1 || $this->havegainloto5==1 || $this->havegainmaryajGratis==1){
    
    

    $reponseRequette=TicketVendu::where('ticket_code_id',$codeSpecifique)->where('tirage_record_id',$this->tirage)
        ->update([
            'winning' => $this->totalGains,
            'is_win' => 1,
            'is_calculated' =>1,
        ]);
     $this->totalGains=0;
     $this->havegain=0;
     $this->havegainmaryaj=0;
     $this->havegainloto3=0;
     $this->havegainloto4=0; 
     $this->havegainloto5=0;
     $this->havegainmaryajGratis=0;

 }else{
    $reponseRequette=TicketVendu::where('ticket_code_id',$codeSpecifique)->where('tirage_record_id',$this->tirage)
    ->update([
        'is_calculated' =>1,
        
    ]);
 } 
 $this->i=$this->i+1;
        }
    });




if($this->foundfiches==1){ 
     return $statut=1;  
}else{
    
    return $statut=0;
 }
 
 

    }
 
 
    public function bolete($gagnants, $ficheData, $borletePrice)
    {
        if (!empty($ficheData[0]['bolete'])) {
            $boleteData = $ficheData[0]['bolete'];
    
            // Initialisation des variables pour la logique quantique
            $multipliers = [
                'premierchiffre' => $borletePrice,
                'secondchiffre' => 20,
                'troisiemechiffre' => 10,
            ];
    
            foreach ($boleteData as $boul) {
                // Extraction des trois chiffres gagnants pour réduire les appels multiples
                $gagnantsChiffres = [
                    'premierchiffre' => $gagnants->premierchiffre,
                    'secondchiffre' => $gagnants->secondchiffre,
                    'troisiemechiffre' => $gagnants->troisiemechiffre,
                ];
    
                // Superposition: Vérification des conditions gagnantes en une seule boucle
                foreach ($gagnantsChiffres as $chiffre => $gagnant) {
                    if (isset($boul['boul' . substr($chiffre, -1)]) && $boul['boul' . substr($chiffre, -1)] == $gagnant) {
                        $montantGagne = $boul['montant'] * $multipliers[$chiffre];
                        $this->totalGains += $montantGagne;
    
                        // Mesure: Une fois un gain détecté, marquer la fiche comme gagnante
                        $this->havegain = 1;
                        break; // Passer au prochain `boul` une fois un gain trouvé
                    }
                }
            }
        } else {
            $this->havegain = 0;
        }
    }
    
 
    public function maryaj($gagnants, $ficheData, $maryajPrice)
    {
        if (!empty($ficheData[1]['maryaj'])) {
            $maryajData = $ficheData[1]['maryaj'];
    
            // Création d'un ensemble de combinaisons gagnantes possibles
            $gagnantCombinations = [
                [$gagnants->premierchiffre, $gagnants->secondchiffre],
                [$gagnants->premierchiffre, $gagnants->troisiemechiffre],
                [$gagnants->secondchiffre, $gagnants->premierchiffre],
                [$gagnants->secondchiffre, $gagnants->troisiemechiffre],
                [$gagnants->troisiemechiffre, $gagnants->premierchiffre],
                [$gagnants->troisiemechiffre, $gagnants->secondchiffre]
            ];
    
            foreach ($maryajData as $fiche) {
                $boul1 = $fiche['boul1'];
                $boul2 = $fiche['boul2'];
    
                // Superposition: Vérification en une seule boucle si la combinaison est gagnante
                foreach ($gagnantCombinations as $combination) {
                    if ($boul1 == $combination[0] && $boul2 == $combination[1]) {
                        $montantGagne = $fiche['montant'] * $maryajPrice;
                        $this->totalGains += $montantGagne;
                        $this->havegainmaryaj = 1;
                        break 2; // Sortir des deux boucles une fois un gain trouvé
                    }
                }
            }
        } else {
            $this->havegainmaryaj = 0;
        }
    }
    
 
    public function loto3($gagnants, $ficheData, $loto3Price)
    {
        if (!empty($ficheData[2]['loto3'])) {
            $loto3Data = $ficheData[2]['loto3'];
    
            // Pré-calcul de la combinaison gagnante
            $combinaisonGagnante = $gagnants->unchiffre . $gagnants->premierchiffre;
    
            foreach ($loto3Data as $fiche) {
                $boul1 = $fiche['boul1'];
    
                // Vérification directe de la correspondance
                if ($boul1 == $combinaisonGagnante) {
                    $montantGagne = $fiche['montant'] * $loto3Price;
                    $this->totalGains += $montantGagne;
                    $this->havegainloto3 = 1;
                    break; // Sortir de la boucle dès qu'une combinaison gagnante est trouvée
                }
            }
        } else {
            $this->havegainloto3 = 0;
        }
    }
    
 
 
    public function loto4($gagnants, $ficheData, $loto4Price)
    {
        if (!empty($ficheData[3]['loto4'])) {
            $loto4Data = $ficheData[3]['loto4'];
            $combinaisonsGagnantes = [
                'option1' => $gagnants->secondchiffre . $gagnants->troisiemechiffre,
                'option2' => $gagnants->secondchiffre . $gagnants->premierchiffre,
                'option3' => $gagnants->premierchiffre . $gagnants->troisiemechiffre
            ];
    
            foreach ($loto4Data as $fiche) {
                $boul1 = $fiche['boul1'];
    
                foreach ($combinaisonsGagnantes as $option => $combinaison) {
                    if (isset($fiche[$option]) && $boul1 == $combinaison) {
                        $montantGagne = $fiche[$option] * $loto4Price;
                        $this->totalGains += $montantGagne;
                        $this->havegainloto4 = 1;
                        break 2; // Sortie des boucles dès qu'une combinaison gagnante est trouvée
                    }
                }
            }
        } else {
            $this->havegainloto4 = 0;
        }
    }
    
    public function loto5($gagnants, $ficheData, $loto5Price)
{
    if (!empty($ficheData[4]['loto5'])) {
        $loto5Data = $ficheData[4]['loto5'];
        $combinaisonsGagnantes = [
            'option1' => $gagnants->unchiffre . $gagnants->premierchiffre . $gagnants->secondchiffre,
            'option2' => $gagnants->unchiffre . $gagnants->premierchiffre . $gagnants->troisiemechiffre,
            'option3' => substr($gagnants->premierchiffre, -1) . $gagnants->secondchiffre . $gagnants->troisiemechiffre
        ];

        foreach ($loto5Data as $fiche) {
            $boul1 = $fiche['boul1'];

            foreach ($combinaisonsGagnantes as $option => $combinaison) {
                if (isset($fiche[$option]) && $boul1 == $combinaison) {
                    $montantGagne = $fiche[$option] * $loto5Price;
                    $this->totalGains += $montantGagne;
                    $this->havegainloto5 = 1;
                    break 2; // Sortie des boucles dès qu'une combinaison gagnante est trouvée
                }
            }
        }
    } else {
        $this->havegainloto5 = 0;
    }
}


public function mariagegratis($gagnants, $ficheData, $maryajgratis)
{
    if (!empty($ficheData[5]['mariage_gratis'])) {
        $maryajData = $ficheData[5]['mariage_gratis'];

        // Créer un tableau de combinaisons gagnantes pour vérification rapide
        $combinaisonsGagnantes = [
            $gagnants->premierchiffre . $gagnants->secondchiffre,
            $gagnants->premierchiffre . $gagnants->troisiemechiffre,
            $gagnants->secondchiffre . $gagnants->premierchiffre,
            $gagnants->secondchiffre . $gagnants->troisiemechiffre,
            $gagnants->troisiemechiffre . $gagnants->premierchiffre,
            $gagnants->troisiemechiffre . $gagnants->secondchiffre
        ];

        foreach ($maryajData as $fiche) {
            $boul1 = $fiche['boul1'];
            $boul2 = $fiche['boul2'];
            $combinaisonActuelle = $boul1 . $boul2;

            // Vérifier si la combinaison actuelle est gagnante
            if (in_array($combinaisonActuelle, $combinaisonsGagnantes)) {
                $montantGagne = $maryajgratis;
                $this->totalGains += $montantGagne;
                $this->havegainmaryajGratis = 1;
                break; // Sortir de la boucle dès qu'une combinaison gagnante est trouvée
            }
        }
    } else {
        $this->havegainmaryajGratis = 0;
    }
}


}
