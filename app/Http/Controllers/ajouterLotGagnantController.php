<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoulGagnant;
use App\Http\Controllers\executeTirageController;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\tirage_record;
use Illuminate\Support\Carbon;
class ajouterLotGagnantController extends Controller
{
    
    public function index(){
    $list=BoulGagnant::where('compagnie_id',session('loginId'))->orderBy('created_at', 'desc')->get();
    return view('list-lo',compact('list'));
    }
    public function ajouterlo(Request $request){
          $id=$request->id;
          
          $list=tirage_record::where('compagnie_id',session('loginId'))->get();
          $record=BoulGagnant::where('compagnie_id',session('loginId'))->where('created_',$id)->first();
          return view('ajoutelo',compact('list','record'));
    }
    
    public function store(Request $request){

 
        $tirageId=$request->input('tirage');
        $date=$request->input('date');
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

                //verifaction exist lo
                $resultExist=$this->ifExisteLo($tirageId,$date);
                if($resultExist){
                    notify()->error('Lo sa existe deja');
                    return redirect()->back();
                }


                //fin
                  // Vérifier si l'heure du serveur est supérieure ou égale à $heureTirage
                $compagnieId=session('loginId');
                $heureTirage = tirage_record::where('tirage_id', $tirageId)->where('compagnie_id',$compagnieId)->value('hour');
   
     
                $heureServeur = Carbon::now()->format('H:i:s');
                
                if (Carbon::parse($heureServeur)->gte(Carbon::parse($heureTirage))) {
                    notify()->error('Le pou tiraj sa fenmen poko rive');
                    return redirect()->back();
                }
                  //fin de la verification

        $formattedDate = $date; //Carbon::createFromFormat('m-d-Y', $date)->format('Y-m-d');
        try {
            $reponseadd=BoulGagnant::create([
                'tirage_id'=>$request->input('tirage'),
                'compagnie_id' => session('loginId'),
                'unchiffre' => $unchiffre,
                'premierchiffre'=>$premierchiffre,
                'secondchiffre'=>$secondchiffre,
                'troisiemechiffre'=>$troisiemechiffre,
                'etat'=>'true',
                'created_'=>$formattedDate
            ]);
    
           if($reponseadd){
            $class=new executeTirageController();
            $reponse=$class->verification($tirageId,$date);
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
           
    }


function ifExisteLo($id,$date){
    $exist=BoulGagnant::where('compagnie_id',session('loginId'))->where('tirage_id',$id)->where('created_', $date)->get();
    if($exist){
        return true;
    }else{
        return false;
    }
  
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


public function modifierlo(Request $request){
    $tirageId=$request->input('tirage');
    $date=$request->input('date');
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


    $Boulgnant=BoulGagnant::where('compagnie_id',session('loginId'))->where('created_',$date);
    if($Boulgnant){
    try {
        $reponseadd=$Boulgnant->update([
            'unchiffre' => $unchiffre,
            'premierchiffre'=>$premierchiffre,
            'secondchiffre'=>$secondchiffre,
            'troisiemechiffre'=>$troisiemechiffre,
            'etat'=>'true',
        ]);

       
       if($reponseadd){
        $class=new executeTirageController();
        $reponse=$class->verification($tirageId,$date);
       if($reponse=='1'){
        notify()->success('Lo Modifier et triyaj sikese');
        return redirect()->back();
       }elseif($reponse=='-1'){
        notify()->error('Pa gen fich ki jwe pou tiraj sa');
        return redirect()->back();
       }elseif($reponse=='0'){
        notify()->error('Le pou tiraj fenmen poko rive');
        return redirect()->back();
       }
       
       }
       notify()->success('Lo Modifier e triyaj sikese');
        return redirect()->route('listlo');
    } catch (\Exception $e) {
        // Gérer l'exception si la création échoue
        notify()->error('erreur dajout',$e);
        return redirect()->back();
      
    }

}else{
    notify()->error('Modification impossible');
    return redirect()->back();
}

}


}
