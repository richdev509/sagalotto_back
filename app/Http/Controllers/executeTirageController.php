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

     $borletePrice =RulesOne::where('compagnie_id', $compagnieId)->value('prix');
     $lotoNames = ['maryaj', 'loto3', 'loto4', 'loto5'];
     $lotoPrices = RulesTwo::whereIn('loto_name', $lotoNames)->pluck('prix', 'loto_name');
     $maryajgratis= maryajgratis::where('compagnie_id',$compagnieId)->where('etat',1)->value('prix');
     $tirageName = $tirage;
     
     // Récupération des numéros gagnants pour le tirage spécifique
     $formattedDate = $date;
     $gagnants = BoulGagnant::where('compagnie_id', $compagnieId)
          ->where('tirage_id', $tirageName)
          ->whereDate('created_', $formattedDate)
          ->first();
      
     //Recupere liste des codes vendu pour le jour en question.
     $codes = ticket_code::where('compagnie_id', $compagnieId)
    ->whereDate('created_at', $formattedDate)
    ->pluck('code')
    ->toArray();
    //dd($codes);
    $fiches="";
   if($codes){
 // Récupérer les tickets vendus
 $fiches= TicketVendu::whereIn('ticket_code_id', $codes)
 ->where('tirage_record_id', $tirageName)
 ->get();
 
   }
   
    
    
 
 // Parcours des fiches
$ficheDatas="";
$i=1;
if($fiches!=""){


 $rules =RulesOne::where('compagnie_id', $compagnieId)->get();
 $rules2 =maryajgratis::where('compagnie_id', $compagnieId)->get();

 foreach ($fiches as $fiche) {
    $numerobranch = $fiche->ticketcode->branch_id;
    $borletePrice = 50;
    // Trouver la règle correspondant au branch_id
    $rule = $rules->firstWhere('branch_id', $numerobranch);
    $rule2=$rules2->firstWhere('branch_id',$numerobranch);
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
     
     $reponse=$this->bolete($gagnants,$ficheData,$borletePrice);
     
     $reponse= $this->maryaj($gagnants,$ficheData,$lotoPrices['maryaj']);
    
    $this->loto3($gagnants,$ficheData,$lotoPrices['loto3']);
    
    $this->loto4($gagnants,$ficheData,$lotoPrices['loto4']);
   
    $this->loto5($gagnants,$ficheData,$lotoPrices['loto5']);

    $this->mariagegratis($gagnants,$ficheData,$maryajgratis);
    
    
    $this->totalgains[$i][]=$this->totalGains;
    $this->totalgains[$i][]=$codeSpecifique;
   
     if($this->havegain==1 || $this->havegainmaryaj==1 || $this->havegainloto3==1 || $this->havegainloto4==1 || $this->havegainloto5==1 || $this->havegainmaryajGratis==1){
        
        

        $reponseRequette=TicketVendu::where('ticket_code_id',$codeSpecifique)->where('tirage_record_id',$tirage)
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
        $reponseRequette=TicketVendu::where('ticket_code_id',$codeSpecifique)->where('tirage_record_id',$tirage)
        ->update([
            'is_calculated' =>1,
            
        ]);
     } 
     $i=$i+1;
     
     
 
 }
}


if($codes){
     return $statut=1;  
}else{
    
    return $statut=0;
 }
 

 
    }
 
 
    public function bolete($gagnants,$ficheData,$borletePrice){
        
        if (isset($ficheData[0]['bolete']) && !empty($ficheData[0]['bolete'])) {
            // Accès à bolete dans le premier élément du tableau $ficheData
            $boleteData = $ficheData[0]['bolete'];
        $bo="ok2";
         $i=1;
         $y=1;
         $is_one=0;
         $is_two=0;
         $is_tree=0;
         foreach ($boleteData as $boul) {
             foreach ($boul as $cle => $valeur) {
                 if (substr($cle, 0, 4) === 'boul') {
                     $boulGagnante = $valeur;
                     $bo=$boulGagnante;
                     
                     if($boulGagnante == $gagnants->premierchiffre){
                         $montantGagne = $boul['montant'] * $borletePrice;
                         $this->totalGains=$this->totalGains+$montantGagne;
                         $is_one=1;
                        
 
                     }
                     
                     if ($boulGagnante == $gagnants->secondchiffre){
                         $montantGagne = $boul['montant'] * 20;
                         $this->totalGains=$this->totalGains+$montantGagne;
                         $is_two=1;
                     }
                     if ($boulGagnante == $gagnants->troisiemechiffre){
                         $montantGagne = $boul['montant'] * 10;
                         $this->totalGains=$this->totalGains+$montantGagne;
                         $is_tree=1;
                     }
                    
                     if($is_one==1 || $is_two==1 || $is_tree==1){
                       
                         $this->havegain=1;
                         $i=$i+1;
                     }else{
                        // $this->havegain=0;
                     }
                    
 
                    
                 }
             }
         }
     }else{
      
         $this->havegain=0;
     }
 
    }
 
    public function maryaj($gagnants,$ficheData,$maryajPrice){
        $do="ok";
        if (isset($ficheData[1]['maryaj']) && !empty($ficheData[1]['maryaj'])) 
     {   $maryajData = $ficheData[1]['maryaj'];
        
     foreach ($maryajData as $fiche) {
        $do=$fiche['boul2'];
         $boul1 = $fiche['boul1'];
         $boul2 = $fiche['boul2'];
           
         // Vérification si la combinaison boul1 et boul2 est gagnante
         if (
            ($boul1 == $gagnants->premierchiffre && $boul2 == $gagnants->secondchiffre) ||
         ($boul1 == $gagnants->premierchiffre && $boul2 == $gagnants->troisiemechiffre) ||
         ($boul1 == $gagnants->secondchiffre && $boul2 == $gagnants->premierchiffre) ||
         ($boul1 == $gagnants->secondchiffre && $boul2 == $gagnants->troisiemechiffre) ||
         ($boul1 == $gagnants->troisiemechiffre && $boul2 == $gagnants->premierchiffre) ||
         ($boul1 == $gagnants->troisiemechiffre && $boul2 == $gagnants->secondchiffre)
         ) {
               
             $montantGagne = $fiche['montant'] * $maryajPrice;
             $this->totalGains=$this->totalGains+$montantGagne;
             $this->havegainmaryaj=1;
             
         }else{
          
         }
        
      }
      }else{
        $this->havegainmaryaj=0;
       }
       
 }
 
    public function loto3($gagnants,$ficheData,$loto3Price){
        if (isset($ficheData[2]['loto3']) && !empty($ficheData[2]['loto3'])) 
        {   $loto3Data = $ficheData[2]['loto3'];
     foreach ($loto3Data as $fiche) {
         $boul1 = $fiche['boul1'];
     
         // Vérification si le boul1 correspond à la combinaison de unchiffre et premierchiffre
         $combinaisonGagnante = $gagnants->unchiffre . $gagnants->premierchiffre;
     
         if ($boul1 == $combinaisonGagnante) {
             // Le boul1 correspond à la combinaison gagnante, multiplier le montant par le prix de "loto3"
             $montantGagne = $fiche['montant'] * $loto3Price;
             $this->totalGains=$this->totalGains+$montantGagne;
           
             $this->havegainloto3=1;
         }else{
           
         }
       
     }
 
    }else{
      $this->havegainloto3=0;
    }
 }
 
 
 public function loto4($gagnants,$ficheData,$loto4Price){
    if (isset($ficheData[3]['loto4']) && !empty($ficheData[3]['loto4'])) 
    {   $loto4Data = $ficheData[3]['loto4'];

     $firsttest=0;
     $options = [];
     foreach ($loto4Data as $fiche) {
         $boul1 = $fiche['boul1'];

         if(isset($fiche['option1'])){
            $combinaisonGagnante = $gagnants->secondchiffre . $gagnants->troisiemechiffre;
            if ($boul1 == $combinaisonGagnante) {
                $montantGagne = $fiche['option1'] * $loto4Price;
                $this->totalGains=$this->totalGains+$montantGagne;
                $firsttest=1;
                $this->havegainloto4=1;
            }else{
                
            }
         }
          // Vérification si le boul1 correspond à la combinaison de secondchiffre et troisiemechiffre
     
    
     if(isset($fiche['option2'])) {
         $combinaisonGagnante2 = $gagnants->secondchiffre . $gagnants->premierchiffre;
                if ($boul1 == $combinaisonGagnante2) {
         $montantGagne = $fiche['option2'] * $loto4Price;
         $this->totalGains=$this->totalGains+$montantGagne;
         $firsttest=1;
         $this->havegainloto4=1;
     }
     }
     if(isset($fiche['option3'])) {
         $combinaisonGagnante3 = $gagnants->premierchiffre . $gagnants->troisiemechiffre;
     if ($boul1 == $combinaisonGagnante3) {
         $montantGagne = $fiche['option3'] * $loto4Price;
         $this->totalGains=$this->totalGains+$montantGagne;
         $firsttest=1;
         $this->havegainloto4=1;
     }
       
        
     }

     
    }
  }else{
    $this->havegainloto4=0;
  }
 
    }
 
 
 public function loto5($gagnants,$ficheData,$loto5Price){

    $do="ok";
    if (isset($ficheData[4]['loto5']) && !empty($ficheData[4]['loto5'])) 
    {   
        
        
        $loto5Data = $ficheData[4]['loto5'];
         $options = [];
         $firsttest=0;
        
     foreach ($loto5Data as $fiche) {
        
         $boul1 = $fiche['boul1'];
        // $boul2=$fiche['boul2'];

         if(isset($fiche['option1'])){

         
       $combinaisonGagnante = $gagnants->unchiffre . $gagnants->premierchiffre . $gagnants->secondchiffre;
 
       if ($boul1 == $combinaisonGagnante) {
     // La concaténation de 3 chiffres dans boul1 et boul2 correspond à la combinaison gagnante, multiplier le montant par le prix de "loto5"
     $montantGagne = $fiche['option1'] * $loto5Price;
     $this->totalGains=$this->totalGains+$montantGagne;
     $firsttest=1;
     $this->havegainloto5=1;
       }
    }
 
     if (isset($fiche['option2']) && $boul1 == $gagnants->unchiffre . $gagnants->premierchiffre . $gagnants->troisiemechiffre) {
     // L'option2 est présente et correspond à la combinaison gagnante, multiplier le montant par le prix de l'option2
     $montantGagne = $fiche['option2'] * $loto5Price;
     $this->totalGains=$this->totalGains+$montantGagne;
     /*$options[] = [
         'option2' => $fiche['option2']
     ];*/
     $this->havegainloto5=1;
     } 
     if (isset($fiche['option3']) && $boul1  == substr($gagnants->premierchiffre, -1) . $gagnants->secondchiffre . $gagnants->troisiemechiffre) {
     // L'option3 est présente et correspond à la combinaison gagnante, multiplier le montant par le prix de l'option3
     $montantGagne = $fiche['option3'] * $loto5Price;
     $this->totalGains=$this->totalGains+$montantGagne;
     /*$options[] = [
         'option3' => $fiche['option3']
     ];*/
     $this->havegainloto5=1;
     } 
     
   
 
     
   }
 }else{
    $this->havegainloto5=0;
 }
 

 }

 public function mariagegratis($gagnants,$ficheData, $maryajgratis){
    if(isset($ficheData[5]['mariage_gratis']) && !empty($ficheData[5]['mariage_gratis'])){
        $maryajData = $ficheData[5]['mariage_gratis'];
        
        foreach ($maryajData as $fiche) {
           
            $boul1 = $fiche['boul1'];
            $boul2 = $fiche['boul2'];
            if (
                ($boul1 == $gagnants->premierchiffre && $boul2 == $gagnants->secondchiffre) ||
                ($boul1 == $gagnants->premierchiffre && $boul2 == $gagnants->troisiemechiffre) ||
                ($boul1 == $gagnants->secondchiffre && $boul2 == $gagnants->premierchiffre) ||
                ($boul1 == $gagnants->secondchiffre && $boul2 == $gagnants->troisiemechiffre) ||
                ($boul1 == $gagnants->troisiemechiffre && $boul2 == $gagnants->premierchiffre) ||
                ($boul1 == $gagnants->troisiemechiffre && $boul2 == $gagnants->secondchiffre) 
            ) {
                $montantGagne =  $maryajgratis;
                $this->totalGains=$this->totalGains+$montantGagne;
                $this->havegainmaryajGratis=1;
            }else{
               
            }
            
        }
    }else{
        $this->havegainmaryajGratis=0;
    }
   
 }
}
