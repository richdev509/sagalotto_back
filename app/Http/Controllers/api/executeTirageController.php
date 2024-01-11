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

}

   }


   public function bolete($gagnants,$ficheData,$borletePrice){
    if (isset($ficheData['bolete'])) {
        foreach ($ficheData['bolete'] as $boul) {
            foreach ($boul as $cle => $valeur) {
                if (substr($cle, 0, 4) === 'boul') {
                    $boulGagnante = $valeur;

                    // Vérification si la boul est gagnante
                    if (
                        $boulGagnante == $gagnants->premyechiffre ||
                        $boulGagnante == $gagnants->secondchiffre ||
                        $boulGagnante == $gagnants->troisiemechiffre
                    ) {
                        // La boul est gagnante, multiplier le montant par le prix du borlete
                        $montantGagne = $boul['montant'] * $borletePrice;
                        $this->totalGains=$this->totalGains+$montantGagne;
                        // Vous pouvez maintenant utiliser $montantGagne comme nécessaire
                    }
                }
            }
        }
    }

   }

   public function maryaj($gagnants,$ficheData,$maryajPrice){
    if (isset($ficheData['maryaj'])) 
    {
    foreach ($ficheData['maryaj'] as $fiche) {
        $boul1 = $fiche['boul1'];
        $boul2 = $fiche['boul2'];
    
        // Vérification si la combinaison boul1 et boul2 est gagnante
        if (
            ($boul1 == $gagnants->premyechiffre && $boul2 == $gagnants->secondchiffre) ||
            ($boul1 == $gagnants->premyechiffre && $boul2 == $gagnants->troisiemechiffre) ||
            ($boul1 == $gagnants->secondchiffre && $boul2 == $gagnants->premyechiffre) ||
            ($boul1 == $gagnants->secondchiffre && $boul2 == $gagnants->troisiemechiffre) ||
            ($boul1 == $gagnants->troisiemechiffre && $boul2 == $gagnants->premyechiffre) ||
            ($boul1 == $gagnants->troisiemechiffre && $boul2 == $gagnants->secondchiffre) 
        ) {
            // La combinaison boul1 et boul2 est gagnante, multiplier le montant par le prix de "maryaj"
            $montantGagne = $fiche['montant'] * $maryajPrice;
            $this->totalGains=$this->totalGains+$montantGagne;
            // Vous pouvez maintenant utiliser $montantGagne comme nécessaire
        }
    }
   }
}

   public function loto3($gagnants,$ficheData,$loto3Price){
    if(isset($ficheData)){
    foreach ($ficheData['loto3'] as $fiche) {
        $boul1 = $fiche['boul1'];
    
        // Vérification si le boul1 correspond à la combinaison de unchiffre et premierchiffre
        $combinaisonGagnante = $gagnants->unchiffre . $gagnants->premyechiffre;
    
        if ($boul1 == $combinaisonGagnante) {
            // Le boul1 correspond à la combinaison gagnante, multiplier le montant par le prix de "loto3"
            $montantGagne = $fiche['montant'] * $loto3Price;
            $this->totalGains=$this->totalGains+$montantGagne;
            // Vous pouvez maintenant utiliser $montantGagne comme nécessaire
        }
    }

   }
}


public function loto4($gagnants,$ficheData,$loto4Price){
 if(isset($ficheData['loto4'])){
    foreach ($ficheData['loto4'] as $fiche) {
        $boul1 = $fiche['boul1'];
         // Vérification si le boul1 correspond à la combinaison de secondchiffre et troisiemechiffre
    $combinaisonGagnante = $gagnants->secondchiffre . $gagnants->troisiemechiffre;
    if ($boul1 == $combinaisonGagnante) {
        $montantGagne = $fiche['montant'] * $loto4Price;
        $this->totalGains=$this->totalGains+$montantGagne;
    }

    if(isset($fiche['option2'])) {
        // L'option2 est présente, multiplier le montant par le prix de l'option2
        $montantGagne = $fiche['montant'] * $fiche['option2'];
        $this->totalGains=$this->totalGains+$montantGagne;
    }
    if(isset($fiche['option3'])) {
        // L'option2 est présente, multiplier le montant par le prix de l'option2
        $montantGagne = $fiche['montant'] * $fiche['option3'];
        $this->totalGains=$this->totalGains+$montantGagne;
    }
   }
 }

   }


public function loto5($gagnants,$ficheData,$loto5Price){
    if(isset($ficheData)){

   
    foreach ($ficheData['loto5'] as $fiche) {
        $boul1 = $fiche['boul1'];
        $boul2=$fiche['boul2'];
      $combinaisonGagnante = $gagnants->unchiffre . $gagnants->premyechiffre . $gagnants->secondchiffre;

      if ($boul1 . $boul2 == $combinaisonGagnante) {
    // La concaténation de 3 chiffres dans boul1 et boul2 correspond à la combinaison gagnante, multiplier le montant par le prix de "loto5"
    $montantGagne = $fiche['montant'] * $loto5Price;
    } 
    if (isset($fiche['option2']) && $boul1 . $boul2 == $gagnants->unchiffre . $gagnants->premyechiffre . $gagnants->troisiemechiffre) {
    // L'option2 est présente et correspond à la combinaison gagnante, multiplier le montant par le prix de l'option2
    $montantGagne = $fiche['montant'] * $fiche['option2'];
    $this->totalGains=$this->totalGains+$montantGagne;
    } 
    if (isset($fiche['option3']) && $boul1 . $boul2 == substr($gagnants->premyechiffre, -1) . $gagnants->secondchiffre . $gagnants->troisiemechiffre) {
    // L'option3 est présente et correspond à la combinaison gagnante, multiplier le montant par le prix de l'option3
    $montantGagne = $fiche['montant'] * $fiche['option3'];
    $this->totalGains=$this->totalGains+$montantGagne;
    } 

    }
  }
}



}
