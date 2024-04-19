<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;



    public function test(){
        // Récupérer l'heure actuelle du serveur
        $heureServeur = now();
    
        // Sélectionnez les codes distincts de la table tirage_record
        $codes = DB::table('tirage_record')
            ->select('code')->where('compagnie_id','1')
            ->distinct()
            ->get();
               
        $resultats = [];
    
        foreach ($codes as $code) {
            // Récupérer tous les enregistrements pour ce code
            $enregistrements = DB::table('tirage_record')
                ->where('code', $code->code)
                ->get();
    
            // Initialiser la différence de temps minimale à une valeur très grande
            $differenceMinimale = PHP_INT_MAX;
            $enregistrementPlusProche = null;
    
            foreach ($enregistrements as $enregistrement) {
                // Calculer la différence de temps entre l'enregistrement et l'heure actuelle du serveur
                $difference = abs(strtotime($enregistrement->hour) - strtotime($heureServeur));
    
                // Vérifier si cette différence est plus petite ou égale à la différence minimale actuelle
                if ($difference <= $differenceMinimale) {
                    // Mettre à jour la différence minimale et l'enregistrement le plus proche si nécessaire
                    $differenceMinimale = $difference;
                    $enregistrementPlusProche = $enregistrement;
                }
            }
    
            // Ajouter l'enregistrement le plus proche à notre tableau de résultats
            if ($enregistrementPlusProche) {
                $resultats[] = $enregistrementPlusProche;
            }
        }
    
        
        $resultatsNonDepasses = [];

        // Vérifier si l'heure du serveur a déjà dépassé l'heure de chaque enregistrement dans $resultats
        foreach ($resultats as $enregistrement) {
            if (strtotime($heureServeur) <= strtotime($enregistrement->hour)) {
                $resultatsNonDepasses[] = $enregistrement;
            }
        }
    
        dd($resultatsNonDepasses,$resultats);
       
        //ici recuperer les resultat de $resultatsNonDepasses  a retourner dans la reponse json

    }
    
    
    
    
    


}
