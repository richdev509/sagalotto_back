<?php

namespace App\Http\Controllers;

use App\Models\company;
use App\Models\user;
use App\Http\Requests\StorecompanyRequest;
use App\Http\Requests\UpdatecompanyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\switchboul;
use App\Models\tirage;
use App\Models\tirage_record;

class updateSwitchController extends Controller
{

    public function  index(Request $request){
         if($request->input('optionsRadios')){
            $boul=switchboul::where('id_compagnie',session('loginId'))->where('tirage_id',$request->input('optionsRadios'))->get();
         }else{
            $boul=switchboul::where('id_compagnie',session('loginId'))->where('tirage_id','1')->get();
        }
        $tirage=tirage_record::where('compagnie_id',session('loginId'))->get();
        return view('blocageboul',['boul'=>$boul, 'tirageRecord'=>$tirage]);
    }



    public function updateSwitch(Request $request)
    {

        $number = $request->input('number');
        $status = $request->input('status');
        $tirage= $request->input('selectedOptionValue');

        if($status==0){
         $status= $this->deletedboul($number);
         if($status==true){
            return response()->json(['number'=>$number,'statut'=> 0],200); // Réponse JSON
         }else{
            return response()->json(['number'=>$number,'statut'=> 1],200); // Réponse JSON
         
         }
        }elseif($status==1){
          $status=$this->store($number,$tirage);
          if($status){
            return response()->json(['number'=>$number,'statut'=> 1],200); // Réponse JSON
       
          }elseif($status==false){
            return response()->json(['number'=>$number,'statut'=> 0],200); // Réponse JSON
         
          }
        }
     
        
       
    }


    public function store($id,$tirageid){
        try {
            $blockboul = Switchboul::create([
                'id_compagnie' => session('loginId'),
                'boul' => $id,
                'tirage_id'=>$tirageid,
            ]);
    
            return $blockboul; // Retourne le modèle créé en cas de succès
        } catch (\Exception $e) {
            // Gérer l'exception si la création échoue
            return false;
        }
    }

    public function deletedboul($id)
    {
        $statut = Switchboul::where('boul', $id)
            ->where('id_compagnie', session('loginId'))
            ->delete();
    
        return $statut ? true : false;
    }
    
}
