<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\tirage_record;
use Illuminate\Contracts\Session\Session;
use App\Models\TicketVendu;
use App\Models\ticket_code;
use Illuminate\Support\Facades\Log;  


class statistiquegeneralController extends Controller
{


   public $result = [];
   public  $resultmaryaj = [];
   public  $resultloto3 = [];
    public $resultloto4 = [];
    public $resultloto5 = [];

    public function viewpage()
    {
        //$list = tirage_record::where('compagnie_id', session('loginId'))->get();
        $data=$this->getstatistiquegeneral();
        //dd($data);
        $datatype = DB::table('listejwet')->get();
        $datarecord=tirage_record::where('compagnie_id',session('loginId'))->get();
        $datablock=DB::table('limit_prix_boul')->where('compagnie_id',session('loginId'))->get();
       // dd($datablock);
        return view('statistique.statistiquegeneral',compact('data','datarecord','datablock','datatype'));
    }
    public function getstatistiquegeneral()
    {
            $result = $this->getfiches();
            if ($result != "") {
                $resulta = $this->Calculnombrefois($result);
                return $resulta;
           
        }
    }

    public function getfiches()
    {

        $formattedDate = now()->toDateString();
        $codes = ticket_code::where('compagnie_id', session('loginId'))
            ->whereDate('created_at', '2024-04-22')//2024-04-22
            ->pluck('code')
            ->toArray();

        $fiches = "";
        if ($codes) {
            // Récupérer les tickets vendus
            $fiches = TicketVendu::whereIn('ticket_code_id', $codes)
                ->get();
        }

        return $fiches;
    }

    public function Calculnombrefois($fiches)
    {

        
        foreach ($fiches as $fiche) {
            $name=tirage_record::where('id',$fiche->tirage_record_id)->value('name');
            $ficheData = json_decode($fiche->boule, true);
           // dd($name);
            $this->borlette($ficheData,$name);
            $this->maryaj($ficheData,$name);
            $this->loto3($ficheData,$name);
            $this->loto4($ficheData,$name);
            $this->loto5($ficheData,$name);
        }
        
        $results=[
            'result' => $this->result,
            'resulmaryaj' => $this->resultmaryaj,
            'resultloto3' => $this->resultloto3,
            'resultloto4' =>$this->resultloto4,
            'resultloto5' => $this->resultloto5,
        ];
       
        return $results;
    }

      

        
        


           public  function borlette($ficheData,$name){
                     $var=1;
                     $boulbolet=null;
                     $montant=null;
                    if (isset($ficheData[0]['bolete']) && !empty($ficheData[0]['bolete'])) {
                        $boleteData = $ficheData[0]['bolete'];
                        foreach ($boleteData as $boul) {
                            foreach ($boul as $cle => $valeur) {
                                if (substr($cle, 0, 5) === 'boul1') {
                                    
                                    $boulbolet = $valeur;
                                    $montant = $boul['montant'];
                                    //Log::info("Processing fiche with tirage name: $boulbolet : $name");
                                    // Vérifie si le $boulchiffre est déjà dans le tableau $this->result
                                    if (isset($this->result[$boulbolet][$name])) {


                                        
                                        $this->result[$boulbolet][$name]['montant'] += $montant;
                                        $this->result[$boulbolet][$name]['nombre'] += 1;
                                        
                                        
                                    } else {
                                        $this->result[$boulbolet][$name] = [
                                            'montant' => $montant,
                                            'nombre' => 1,
                                            'tirage' => $name
                                        ];
                                    }
                                   
                                }
                            }
                        }
                    } 
                    
                }

               public  function loto3($ficheData,$name){ 
                     $var=1;
                    if (isset($ficheData[2]['loto3']) && !empty($ficheData[2]['loto3'])) {
                        $boulchiffre="";
                        $loto3Data = $ficheData[2]['loto3'];
                        foreach ($loto3Data as $fiche) {
                            $boulchiffre = $fiche['boul1'];
                            $montant = $fiche['montant'];
                            // Vérification si le boul1 correspond à la combinaison de unchiffre et premierchiffre
                            if($boulchiffre!="" && $montant!="" ){
                               
                            if (isset($this->resultloto3[$boulchiffre][$name])) {
                                // Si oui, additionne le montant et incrémente le compteur
                                //dd( $this->resultloto3[$boul1]['montant']);
                                $this->resultloto3[$boulchiffre][$name]['montant'] +=$montant;
                                $this->resultloto3[$boulchiffre][$name]['nombre'] += 1;
                            } else {
                                // Sinon, initialise le montant et le compteur
                                
                                  // Ajoute le chiffre au tableau
                                $this->resultloto3[$boulchiffre][$name] = ['montant' => $montant, 'nombre' => 1, 'tirage'=>$name];
                               // dd( $this->resultloto3[$boulchiffre]['montant']);
                            }
                        }
                        }
                       // dd($this->resultloto3[$boulchiffre]);
                    }
                    
                }

           public function loto4($ficheData,$name){
               

                    if (isset($ficheData[3]['loto4']) && !empty($ficheData[3]['loto4'])) {
                        $loto4Data = $ficheData[3]['loto4'];

                        foreach ($loto4Data as $fiche) {
                            $boullot4 = $fiche['boul1'];

                            if (isset($fiche['option1'])) {
                                $montant = $fiche['option1'];

                                if (isset($this->resultloto4[$boullot4][$name])) {
                                    // Si oui, additionne le montant et incrémente le compteur
                                    
                                    $this->resultloto4[$boullot4][$name]['montant'] += $montant;
                                    $this->resultloto4[$boullot4][$name]['nombre'] += 1;
                                } else {
                                    // Sinon, initialise le montant et le compteur
                                     // Ajoute le chiffre au tableau
                                    $this->resultloto4[$boullot4][$name] = ['montant' => $montant, 'nombre' => 1, 'tirage'=>$name];
                                }
                                    
                                    
                            }

                            if (isset($fiche['option2'])) {



                              
                                    $montant = $fiche['option2'];

                                    if (isset($this->resultloto4[$boullot4][$name])) {
                                        // Si oui, additionne le montant et incrémente le compteur
                                        $this->resultloto4[$boullot4][$name]['montant'] += $montant;
                                        $this->resultloto4[$boullot4][$name]['nombre'] += 1;
                                    } else {
                                        // Sinon, initialise le montant et le compteur
                                        // Ajoute le chiffre au tableau
                                        $this->resultloto4[$boullot4][$name] = ['montant' => $montant, 'nombre' => 1, 'tirage'=>$name];
                                    }
                               
                            }
                            if (isset($fiche['option3'])) {

                               
                                    $montant = $fiche['option3'];
                                    if (isset($this->resultloto4[$boullot4][$name])) {
                                        // Si oui, additionne le montant et incrémente le compteur
                                        $this->resultloto4[$boullot4][$name]['montant'] += $montant;
                                        $this->resultloto4[$boullot4][$name]['nombre'] += 1;
                                    } else {
                                        // Sinon, initialise le montant et le compteur
                                         // Ajoute le chiffre au tableau
                                        $this->resultloto4[$boullot4][$name] = ['montant' => $montant, 'nombre' => 1, 'tirage'=>$name];
                                    }
                                
                            }

                           
                        }
                    } else {
                    }

                }
              public function maryaj($ficheData,$name){
                    if (isset($ficheData[1]['maryaj']) && !empty($ficheData[1]['maryaj'])) {
                        $maryajData = $ficheData[1]['maryaj'];

                        foreach ($maryajData as $fiche) {
                           
                            $boul1 = $fiche['boul1'];
                            $boul2 = $fiche['boul2'];

                            $chiffre=$boul1 . $boul2 ;
                                $montant = $fiche['montant'];
                                
                                if (isset($this->resultmaryaj[$chiffre][$name])) {
                                    // Si oui, additionne le montant et incrémente le compteur
                                    $this->resultmaryaj[$chiffre][$name]['montant'] += $montant;
                                    $this->resultmaryaj[$chiffre][$name]['nombre'] += 1;
                                } else {
                                    // Sinon, initialise le montant et le compteur
                                    
                                     // Ajoute le chiffre au tableau
                                    $this->resultmaryaj[$chiffre][$name] = ['montant' => $montant, 'nombre' => 1, 'tirage'=>$name];
                                }
                            
                        }
                    }
                }
               public  function loto5($ficheData,$name){
                    if (isset($ficheData[4]['loto5']) && !empty($ficheData[4]['loto5'])) {
                        $loto5Data = $ficheData[4]['loto5'];

                        foreach ($loto5Data as $fiche) {
                            $boul1 = $fiche['boul1'];
                            if (isset($fiche['option1'])) {

                              
                                    $montant = $fiche['option1'];
                                    if (isset($this->resultloto5[$boul1][$name])) {
                                        // Si oui, additionne le montant et incrémente le compteur
                                        $this->resultloto5[$boul1][$name]['montant'] += $montant;
                                        $this->resultloto5[$boul1][$name]['nombre'] += 1;
                                    } else {
                                        // Sinon, initialise le montant et le compteur
                                        // Ajoute le chiffre au tableau
                                        $this->resultloto5[$boul1][$name] = ['montant' => $montant, 'nombre' => 1, 'tirage'=>$name];
                                    }
                                
                            }

                            if (isset($fiche['option2'])) {

                                
                                    $montant = $fiche['option2'];
                                    if (isset($this->resultloto5[$boul1][$name])) {
                                        // Si oui, additionne le montant et incrémente le compteur
                                        $this->resultloto5[$boul1][$name]['montant'] += $montant;
                                        $this->resultloto5[$boul1][$name]['nombre'] += 1;
                                    } else {
                                        // Sinon, initialise le montant et le compteur
                                         // Ajoute le chiffre au tableau
                                        $this->resultloto5[$boul1][$name] = ['montant' => $montant, 'nombre' => 1, 'tirage'=>$name];
                                    }
                                
                            }

                            if (isset($fiche['option3'])) {

                                
                                    $montant = $fiche['option3'];
                                    if (isset($this->resultloto5[$boul1][$name])) {
                                        // Si oui, additionne le montant et incrémente le compteur
                                        $this->resultloto5[$boul1][$name]['montant'] += $montant;
                                        $this->resultloto5[$boul1][$name]['nombre'] += 1;
                                    } else {
                                        // Sinon, initialise le montant et le compteur
                                         // Ajoute le chiffre au tableau
                                        $this->resultloto5[$boul1][$name] = ['montant' => $montant, 'nombre' => 1, 'tirage'=>$name];
                                    }
                                
                            }
                        }
                       
                    }
                }
      

        
  
}
