<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoulGagnant;
use App\Http\Controllers\executeTirageController;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\tirage_record;
class ajouterLotGagnantController extends Controller
{
    
    public function index(){
    $list=BoulGagnant::where('compagnie_id',session('loginId'))->get();
    return view('list-lo',compact('list'));
    }
    public function ajouterlo(){
          $list=tirage_record::where('compagnie_id',session('loginId'))->get();
          return view('ajoutelo',compact('list'));
    }
    
    public function store(Request $request){
        $tirageId=$request->input('tirage');
        $unchiffre=$request->input('unchiffre');
        $premierchiffre=$request->input('premierchiffre');
        $secondchiffre=$request->input('secondchiffre');
        $troisiemechiffre=$request->input('troisiemechiffre');

        $erreurs =$this->validerEntrees($tirageId, $unchiffre, $premierchiffre, $secondchiffre, $troisiemechiffre);

        if ($erreurs) {
             $messagesErreur = implode(', ', $erreurs);
             notify()->error($messagesErreur);
             return redirect()->back();
        }

        try {
            $reponseadd=BoulGagnant::create([
                'tirage_id'=>$request->input('tirage'),
                'compagnie_id' => session('loginId'),
                'unchiffre' => $unchiffre,
                'premierchiffre'=>$premierchiffre,
                'secondchiffre'=>$secondchiffre,
                'troisiemechiffre'=>$troisiemechiffre,
                'etat'=>'true',
            ]);
    
           if($reponseadd){
            $class=new executeTirageController();
        $reponse=$class->verification($tirageId);
        if($reponse=='1'){
            notify()->success('Lo ajout avek sikese');
            return redirect()->back();
        }elseif($reponse=='-1'){
            notify()->error('Pa gen fich ki jwe pou tiraj sa');
            return redirect()->back();
        }elseif($reponse=='0'){
            notify()->error('Le pou tiraj fenmen poko rive');
            return redirect()->back();
        }
           
           }
        } catch (\Exception $e) {
            // Gérer l'exception si la création échoue
            notify()->error('erreur dajout',$e);
            return redirect()->back();
          
        }
           /*
        $class=new executeTirageController();
        $reponse=$class->verification($tirageId);
        if($reponse=='1'){
            return redirect()->route('ajoutlo')->with('success','Lot ajoute avek sikse, Tiraj lan fet');
        }elseif($reponse=='-1'){
            return redirect()->route('ajoutlo')->with('alert','wap eseye mete yon lot ki poko tire');
        }elseif($reponse=='0'){
            return redirect()->route('ajoutlo')->with('alert','Le pou tiraj fenmen poko rive');
     
        }*/
        
    }


    function validerEntrees($tirageId, $unchiffre, $premierchiffre, $secondchiffre, $troisiemechiffre)
{
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
            'premierchiffre' => 'required|integer|digits:2',
            'secondchiffre' => 'required|integer|digits:2',
            'troisiemechiffre' => 'required|integer|digits:2',
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
}
