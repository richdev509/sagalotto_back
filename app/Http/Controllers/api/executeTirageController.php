<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Models\company;
use App\Models\user;
use App\Models\RulesOne;
use App\Models\RulesTwo;
use App\Models\BoulGagnant;
use App\Models\FichesJouer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class executeTirageController extends Controller
{

   public $totalGains=0;
   public $totalMontantJouer=0;
   public $gagnantsData = [];
   public $gagnantsDataParCode = [];
   public $havegain=0;
   public $havegainmaryaj=0;
   public $havegainloto3=0;
   public $havegainloto4=0;
   public $havegainloto5=0;
   public function execute(){
    $totalGains =0;

    $compagnieId = 1;  // Remplacez cela par l'id de votre compagnie spécifique
  
    $borletePrice =RulesOne::where('compagnie_id', $compagnieId)->value('prix');
    $lotoNames = ['maryaj', 'loto3', 'loto4', 'loto5'];  // Remplacez cela par les noms de vos lotos spécifiques

    $lotoPrices = RulesTwo::whereIn('loto_name', $lotoNames)->pluck('prix', 'loto_name');
    
    // Étape 3: Récupération des numéros gagnants de la table 'boulgagnant'
    $tirageName = 'Florida Soir';

    // Récupération des numéros gagnants pour le tirage spécifique
    $gagnants = BoulGagnant::where('compagnie_id', $compagnieId)
    ->where('tirage_name', $tirageName)
    ->first();

    $fiches = FichesJouer::where('compagnie_id', $compagnieId)->get();

// Parcours des fiches
foreach ($fiches as $fiche) {

    $ficheData = json_decode($fiche->fiche_json, true);
    //recuperation de l'id du fiche
    $codeSpecifique=$fiche->idfiche;
    //apel des function.
    $this->bolete($gagnants,$ficheData,$borletePrice);
    $this->maryaj($gagnants,$ficheData,$lotoPrices['maryaj']);
    $this->loto3($gagnants,$ficheData,$lotoPrices['loto3']);
    $this->loto4($gagnants,$ficheData,$lotoPrices['loto4']);
    $this->loto5($gagnants,$ficheData,$lotoPrices['loto5']);
    //generer codeSpecifique peut etre le code fiche fiche ici.
    if($this->havegain==1 || $this->havegainmaryaj==1 || $this->havegainloto3==1 || $this->havegainloto4==1 || $this->havegainloto5==1){
        $this->gagnantsDataParCode[$codeSpecifique][] = $this->gagnantsData;
        $this->gagnantsDataParCode[$codeSpecifique][] = $this->totalGains;

        //initialiser les variable global a l'etat initial
        $this->totalGains=0;
        $this->havegain=0;
        $this->havegainmaryaj=0;
        $this->havegainloto3=0;
        $this->havegainloto4=0; 
        $this->havegainloto5=0;

    }
   

}
//code save gagnantData ici
foreach ($this->gagnantsDataParCode as $codeSpecifique => $donneesGagnantes) {
    // Convertissez les données gagnantes en JSON
    $totalgain = $donneesGagnantes[count($donneesGagnantes) - 1]['totalgain'];
    $jsonGagnantsData = json_encode($donneesGagnantes);
    $idGenerer=1;
    // Enregistrez les données dans la base de données en ajoutant les autre champs necessaire
    FichesJouer::create([
        'idFicheGagnant'=>$idGenerer,
        'idficheprecedent' => $codeSpecifique,
        'totalgain'=>$totalgain,
        'json_data' => $jsonGagnantsData,
    ]);
}
   }


   public function bolete($gagnants,$ficheData,$borletePrice){
    if (isset($ficheData['bolete']) && !empty($ficheData['bolete'])) {

        $i=1;
        foreach ($ficheData['bolete'] as $boul) {
            foreach ($boul as $cle => $valeur) {
                if (substr($cle, 0, 4) === 'boul') {
                    $boulGagnante = $valeur;
                     $is_one=0;
                     $is_two=0;
                     $is_tree=0;
                    if($boulGagnante == $gagnants->premierchiffre){
                        $montantGagne = $boul['montant'] * $borletePrice;
                        $this->totalGains=$this->totalGains+$montantGagne;
                        $is_one=1;

                    }
                    // Vérification si la boul est gagnante
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
                    $this->totalMontantJouer=$this->totalMontantJouer+$boul['montant'];
                    if($is_one==1 || $is_two==1 || $is_tree==1){
                        $this->gagnantsData['bolete'][] = [
                            'boul'.$i.'' => $boul,
                            'montant' =>$boul['montant'],
                            
                        ];
                        $this->havegain=1;
                        $i=$i+1;
                    }else{

                    }
                   

                   
                }
            }
        }
    }else{
        $this->gagnantsData['bolete'][] = [
        ];
    }

   }

   public function maryaj($gagnants,$ficheData,$maryajPrice){
     if (isset($ficheData['maryaj']) && !empty($ficheData['maryaj']))
    {
    foreach ($ficheData['maryaj'] as $fiche) {
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
            // La combinaison boul1 et boul2 est gagnante, multiplier le montant par le prix de "maryaj"
            $montantGagne = $fiche['montant'] * $maryajPrice;
            $this->totalGains=$this->totalGains+$montantGagne;
            $this->gagnantsData['maryaj'][] = [
                'boul1' => $boul1,
                'boul2'=>$boul2,
                'montant' =>$fiche['montant'],
                
            ];
            $this->havegainmaryaj=1;
            // Vous pouvez maintenant utiliser $montantGagne comme nécessaire
        }else{
            $this->gagnantsData['maryaj'][] = [
            ];
          
        }
       
     }
     }else{
      $this->gagnantsData['maryaj'][] = [
      ];
      }
}

   public function loto3($gagnants,$ficheData,$loto3Price){
    if(isset($ficheData['loto3'])&& !empty($ficheData['loto3'])){
    foreach ($ficheData['loto3'] as $fiche) {
        $boul1 = $fiche['boul1'];
    
        // Vérification si le boul1 correspond à la combinaison de unchiffre et premierchiffre
        $combinaisonGagnante = $gagnants->unchiffre . $gagnants->premierchiffre;
    
        if ($boul1 == $combinaisonGagnante) {
            // Le boul1 correspond à la combinaison gagnante, multiplier le montant par le prix de "loto3"
            $montantGagne = $fiche['montant'] * $loto3Price;
            $this->totalGains=$this->totalGains+$montantGagne;
            // Vous pouvez maintenant utiliser $montantGagne comme nécessaire
            $this->gagnantsData['loto3'][] = [
                'boul1' => $boul1,
                'montant' => $fiche['montant'],
                
            ];
            $this->havegainloto3=1;
        }else{
            $this->gagnantsData['loto3'][] = [
                
            ];
           
        }
      
    }

   }else{
    $this->gagnantsData['loto3'][] = [
                
    ];
   }
}


public function loto4($gagnants,$ficheData,$loto4Price){
 if(isset($ficheData['loto4'])){
    $firsttest=0;
    $options = [];
    foreach ($ficheData['loto4'] as $fiche) {
        $boul1 = $fiche['boul1'];
         // Vérification si le boul1 correspond à la combinaison de secondchiffre et troisiemechiffre
    $combinaisonGagnante = $gagnants->secondchiffre . $gagnants->troisiemechiffre;
    if ($boul1 == $combinaisonGagnante) {
        $montantGagne = $fiche['montant'] * $loto4Price;
        $this->totalGains=$this->totalGains+$montantGagne;
        $firsttest=1;
        $this->havegainloto4=1;
    }

    if(isset($fiche['option2'])) {
        // L'option2 est présente, multiplier le montant par le prix de l'option2
        $montantGagne = $fiche['montant'] * $fiche['option2'];
        $this->totalGains=$this->totalGains+$montantGagne;
        $options[] = [
            'option2' => 'option2',
            
        ];
        $this->havegainloto4=1;
    }
    if(isset($fiche['option3'])) {
        // L'option2 est présente, multiplier le montant par le prix de l'option2
        $montantGagne = $fiche['montant'] * $fiche['option3'];
        $this->totalGains=$this->totalGains+$montantGagne;
        $options[] = [
            'option3' => 'option3',
           
        ];
        $this->havegainloto4=1;
    }

    if($firsttest==1 && !empty($options)){
        $this->gagnantsData['loto4'][] = [
            'boul1' => $boul1,
            'montant' => $fiche['montant'],
            'options' => $options,
        ];
    }elseif($firsttest==1 && empty($options)){
        $this->gagnantsData['loto4'][] = [
            'boul1' => $boul1,
            'montant' => $fiche['montant'],
            
        ];
    }elseif($firsttest==0 && !empty($options)){
        $this->gagnantsData['loto4'][] = [
            'boul1' => $boul1,
            'options' => $options,
        ];
    }elseif($firsttest==0 && empty($options)){
        $this->gagnantsData['loto4'][] = [
           
        ];
       
    }
    
   }
 }

   }


public function loto5($gagnants,$ficheData,$loto5Price){
    if(isset($ficheData)){
        $options = [];
        $firsttest=0;
    foreach ($ficheData['loto5'] as $fiche) {
        $boul1 = $fiche['boul1'];
        $boul2=$fiche['boul2'];
      $combinaisonGagnante = $gagnants->unchiffre . $gagnants->premierchiffre . $gagnants->secondchiffre;

      if ($boul1 . $boul2 == $combinaisonGagnante) {
    // La concaténation de 3 chiffres dans boul1 et boul2 correspond à la combinaison gagnante, multiplier le montant par le prix de "loto5"
    $montantGagne = $fiche['montant'] * $loto5Price;
    $firsttest=1;
    $this->havegainloto5=1;
      }


    if (isset($fiche['option2']) && $boul1 . $boul2 == $gagnants->unchiffre . $gagnants->premierchiffre . $gagnants->troisiemechiffre) {
    // L'option2 est présente et correspond à la combinaison gagnante, multiplier le montant par le prix de l'option2
    $montantGagne = $fiche['montant'] * $fiche['option2'];
    $this->totalGains=$this->totalGains+$montantGagne;
    $options[] = [
        'option2' => $fiche['option2']
    ];
    $this->havegainloto5=1;
    } 
    if (isset($fiche['option3']) && $boul1 . $boul2 == substr($gagnants->premierchiffre, -1) . $gagnants->secondchiffre . $gagnants->troisiemechiffre) {
    // L'option3 est présente et correspond à la combinaison gagnante, multiplier le montant par le prix de l'option3
    $montantGagne = $fiche['montant'] * $fiche['option3'];
    $this->totalGains=$this->totalGains+$montantGagne;
    $options[] = [
        'option3' => $fiche['option3']
    ];
    $this->havegainloto5=1;
    } 
    if($firsttest==1 && !empty($options)){
        $this->gagnantsData['loto5'][] = [
            'boul1' => $boul1,
             'boul2'=>$boul2,
             'montant' => $fiche['montant'],
             'options' => $options,
        ];
        $this->havegainloto5=1;
    }elseif($firsttest==1 && empty($options)){
        $this->gagnantsData['loto4'][] = [
            'boul1' => $boul1,
            'boul2'=>$boul2,
            'montant' => $fiche['montant'],
        ];
        $this->havegainloto5=1;
    }elseif($firsttest==0 && !empty($options)){
        $this->gagnantsData['loto5'][] = [
        'boul1' => $boul1,
        'boul2'=>$boul2,
        'options' => $options,
          
        ];
        $this->havegainloto5=1;
    }elseif($firsttest==0 && empty($options)){
        $this->gagnantsData['loto5'][] = [
           
        ];
       
    }
  

    
  }
}


}
}
