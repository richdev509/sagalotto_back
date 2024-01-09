<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\switchboul;
use App\Models\tirage;
use App\Models\tirage_record;


class verificationController extends Controller
{

    public function verifierBoulesNonAutorisees($ficheJeux)
    {
        $idCompagnie = session('idcompagnie');
        $boulesNonAutorisees = [];
    
        // Parcourir chaque type de jeu dans la fiche
        foreach ($ficheJeux as $jeu => $tirages) {
            // Ignorer les types de jeu sans tirages
            if (empty($tirages)) {
                continue;
            }
    
            // Parcourir chaque tirage du jeu
            foreach ($tirages as $tirage) {
                // Parcourir chaque boule dans le tirage
                foreach ($tirage as $key => $value) {
                    if (strpos($key, "boul") !== false && $value) {

                        if (!in_array($value, $boulesNonAutorisees)) {
                        // Vérifier si la boule est bloquée
                        $bouleBloquee = Switchboul::where('boul', $value)
                            ->where('id_compagnie', $idCompagnie)
                            ->exists();
    
                        if ($bouleBloquee) {
                            $boulesNonAutorisees[] = $value;
                        }
                          }
                    }
                }
            }
        }
    
        if (!empty($boulesNonAutorisees)) {
            return [
                'statut' => 1,
                'message' => 'Il y a des boules bloquées.',
                'boules_non_autorisees' => $boulesNonAutorisees,
            ];
        } else {
            return [
                'statut' => 0,
                'message' => 'Toutes les boules sont autorisées.',
            ];
        }
    }







}


