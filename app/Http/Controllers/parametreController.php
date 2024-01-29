<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\maryajgratis;

class parametreController extends Controller
{
   
   public  function indexmaryaj(){
             $data=maryajgratis::where('compagnie_id',session('loginId'))->first();
             return view('parametre.maryajGratis',compact('data'));
    }


    public function store(){
             try{
                   
                $repons = maryajgratis::create([
                    'prix' =>0,
                    'compagnie_id' => session('loginId'),
                    'etat'=>1,
                ]);

                return $repons;
             }catch (\Exception $e) {
                // Gérer l'exception si la mise à jour échoue
                return false;
            }
    }

    public  function updatestatut(Request $request){
             $statut=$request->status;
             $reponse=maryajgratis::where('compagnie_id',session('loginId'))->first();
             
             if($reponse){

                $etat=$reponse->etat;
                $valeur=1;
               
                   
                      if($etat==1){
                     $valeur=0;
                      }
                    try {
                    $reponse->update([
                    'etat'=>$valeur,
               ]);
        
                return response()->json(['statut'=> $reponse->etat],200); // Réponse JSON
                
            } catch (\Exception $e) {
                // Gérer l'exception si la mise à jour échoue
                return response()->json(['statut'=> $reponse->etat],200); // Réponse JSON
            }
        
             }else{
                $reponse=$this->store();
                if($reponse){
                    return response()->json(['statut'=> $reponse->etat],200);
                }else{
                    return response()->json(['statut'=> 0],200);
             
                }
             }
    }
    public function updatePrixMaryajGratis(Request $request){
                    $request->montant;
                    try {
                        // Récupérer le modèle existant que vous souhaitez mettre à jour
                        $maryajIsset = maryajgratis::where('compagnie_id', session('loginId'))
                            ->first();
                    
                        if ($maryajIsset) {
                            // Mettre à jour les champs nécessaires
                            $maryajIsset->update([
                                 'prix'=>$request->montant,
                            ]);
                    
                            notify()->success('Montant ajoute: '.$request->montant);
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
}
