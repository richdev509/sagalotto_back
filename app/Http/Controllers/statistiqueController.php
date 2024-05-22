<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\tirage_record;
use Illuminate\Contracts\Session\Session;
use App\Models\TicketVendu;
use App\Models\ticket_code;


class statistiqueController extends Controller
{


    public function view(){
        $list = tirage_record::where('compagnie_id', session('loginId'))->get();
        return view('statistique.statistiqueunique',compact('list'));
    }
    public function getstatistiqueSimple(Request $request)
    {   
        if ($request->ajax()) {
            $boul = $request->input('boul');
            $tirage = $request->input('user');
            $result = $this->getfiches($tirage);
    
            if ($result != "") {
                $resulta = $this->Calculnombrefois($result, $boul);
                
                return response()->json(array_values($resulta));
            }
        }
    }

    public function getfiches($tirage)
    {

        $formattedDate =now()->toDateString();
        $codes = ticket_code::where('compagnie_id', session('loginId'))
            ->whereDate('created_at', $formattedDate)
            ->pluck('code')
            ->toArray();

        $fiches = "";
        if ($codes) {
            // Récupérer les tickets vendus
            $fiches = TicketVendu::whereIn('ticket_code_id', $codes)
                ->where('tirage_record_id', $tirage)
                ->get();
        }

        return $fiches;
    }

    public function Calculnombrefois($fiches, $chiffre)
    {

        $valeur = (string) $chiffre;
        $nombre_de_caracteres = strlen($valeur);
        $jeuxnombre = [];
       
        $montanttotla = 0;
        //incrimentation
        $i = 1;
        $k = 0;
        $z = 0;
        $zM=0;
        $m=0;
        $kmaryaj=0;
        $nombrefois = 0;
        $nombrefoisM=0;
        //dd($fiches,$chiffre);
        foreach ($fiches as $fiche) {
            $ficheData = json_decode($fiche->boule, true);

            switch ($nombre_de_caracteres) {
                case 2:
                    if (isset($ficheData[0]['bolete']) && !empty($ficheData[0]['bolete'])) {
                        $boleteData = $ficheData[0]['bolete'];
                        foreach ($boleteData as $boul) {
                            foreach ($boul as $cle => $valeur) {
                                if (substr($cle, 0, 4) === 'boul') {
                                    $boulchiffre = $valeur;
                                    $montant = $boul['montant'];
                                    if ($boulchiffre == $chiffre) {

                                        if ($z == 0) {
                                            $jeuxnombre[$i]['type']="Borlette";
                                            $jeuxnombre[$i]['boul'] = $boulchiffre;
                                            $jeuxnombre[$i]['nf'] = $nombrefois + 1;
                                            $jeuxnombre[$i]['montant'] = $montant;
                                            $k = $i;
                                            $z = 1;
                                        } else {
                                            $jeuxnombre[$k]['nf'] = $jeuxnombre[$k]['nf'] + 1;
                                            $jeuxnombre[$k]['montant'] = $jeuxnombre[$k]['montant'] + $montant;
                                        }
                                    }
                                    $i++;
                                }
                            }
                        }
                    } else {
                    }
                    break;

                case 3:
                    if (isset($ficheData[2]['loto3']) && !empty($ficheData[2]['loto3'])) {
                        $loto3Data = $ficheData[2]['loto3'];
                        foreach ($loto3Data as $fiche) {
                            $boul1 = $fiche['boul1'];
                            $montant = $fiche['montant'];
                            // Vérification si le boul1 correspond à la combinaison de unchiffre et premierchiffre


                            if ($boul1 == $chiffre) {
                                if ($z == 0) {
                                    $jeuxnombre[$i]['type']="Loto3";
                                    $jeuxnombre[$i]['boul'] = $boul1;
                                    $jeuxnombre[$i]['nf'] = $nombrefois + 1;
                                    $jeuxnombre[$i]['montant'] = $montant;
                                    $k = $i;
                                    $z = 1;
                                } else {
                                    $jeuxnombre[$k]['nf'] = $jeuxnombre[$k]['nf'] + 1;
                                    $jeuxnombre[$k]['montant'] = $jeuxnombre[$k]['montant'] + $montant;
                                }
                            }
                            $i++;
                        }
                    } else {
                    }
                    break;
                case 4:

                    if (isset($ficheData[3]['loto4']) && !empty($ficheData[3]['loto4'])) {
                        $loto4Data = $ficheData[3]['loto4'];

                        foreach ($loto4Data as $fiche) {
                            $boul1 = $fiche['boul1'];

                            if (isset($fiche['option1'])) {
                                if ($boul1 == $chiffre) {
                                    $montant = $fiche['option1'];

                                    if ($z == 0) {
                                        $jeuxnombre[$i]['type']="Loto4";
                                        $jeuxnombre[$i]['boul'] = $boul1;
                                        $jeuxnombre[$i]['nf'] = $nombrefois + 1;
                                        $jeuxnombre[$i]['montant'] = $montant;
                                        $k = $i;
                                        $z = 1;
                                    } else {
                                        $jeuxnombre[$k]['nf'] = $jeuxnombre[$k]['nf'] + 1;
                                        $jeuxnombre[$k]['montant'] = $jeuxnombre[$k]['montant'] + $montant;
                                    }
                                }
                            }

                            if (isset($fiche['option2'])) {



                                if ($boul1 == $chiffre) {
                                    $montant = $fiche['option2'];

                                    if ($z == 0) {
                                        $jeuxnombre[$i]['type']="Loto4op2";
                                        $jeuxnombre[$i]['boul'] = $boul1;
                                        $jeuxnombre[$i]['nf'] = $nombrefois + 1;
                                        $jeuxnombre[$i]['montant'] = $montant;
                                        $k = $i;
                                        $z = 1;
                                    } else {
                                        $jeuxnombre[$k]['nf'] = $jeuxnombre[$k]['nf'] + 1;
                                        $jeuxnombre[$k]['montant'] = $jeuxnombre[$k]['montant'] + $montant;
                                    }
                                } else {
                                }
                            }
                            if (isset($fiche['option3'])) {

                                if ($boul1 == $chiffre) {
                                    $montant = $fiche['option3'];
                                    if ($z == 0) {
                                        $jeuxnombre[$i]['type']="Loto3op3";
                                        $jeuxnombre[$i]['boul'] = $boul1;
                                        $jeuxnombre[$i]['nf'] = $nombrefois + 1;
                                        $jeuxnombre[$i]['montant'] = $montant;
                                        $k = $i;
                                        $z = 1;
                                    } else {
                                        $jeuxnombre[$k]['nf'] = $jeuxnombre[$k]['nf'] + 1;
                                        $jeuxnombre[$k]['montant'] = $jeuxnombre[$k]['montant'] + $montant;
                                    }
                                } else {
                                }
                            }

                            $i++;
                        }
                    } else {
                    }
                    //mariage
                    if (isset($ficheData[1]['maryaj']) && !empty($ficheData[1]['maryaj'])) {
                        $maryajData = $ficheData[1]['maryaj'];

                        foreach ($maryajData as $fiche) {
                            $do = $fiche['boul2'];
                            $boul1 = $fiche['boul1'];
                            $boul2 = $fiche['boul2'];
                            
                            if ($boul1 . $boul2 == $chiffre ) {
                                $montant = $fiche['montant'];
                                    if ($zM == 0) {
                                        $jeuxnombre[$m]['type']="Maryaj";
                                        $jeuxnombre[$m]['boul'] = $boul1.'x'.$boul2;
                                        $jeuxnombre[$m]['nf'] = $nombrefois + 1;
                                        $jeuxnombre[$m]['montant'] = $montant;
                                        $kmaryaj = $m;
                                        $zM = 1;
                                    } else {
                                        $jeuxnombre[$kmaryaj]['nf'] = $jeuxnombre[$kmaryaj]['nf'] + 1;
                                        $jeuxnombre[$kmaryaj]['montant'] = $jeuxnombre[$kmaryaj]['montant'] + $montant;
                                    }
                            }
                        }
                    }
                    break;

                case 5:
                    if (isset($ficheData[4]['loto5']) && !empty($ficheData[4]['loto5'])) {
                        $loto5Data = $ficheData[4]['loto5'];

                        foreach ($loto5Data as $fiche) {
                            $boul1 = $fiche['boul1'];
                            if (isset($fiche['option1'])) {

                                if ($boul1 == $chiffre) {
                                    $montant = $fiche['option1'];
                                    if ($z == 0) {
                                        $jeuxnombre[$i]['type']="Loto5";
                                        $jeuxnombre[$i]['boul'] = $boul1;
                                        $jeuxnombre[$i]['nf'] = $nombrefois + 1;
                                        $jeuxnombre[$i]['montant'] = $montant;
                                        $k = $i;
                                        $z = 1;
                                    } else {
                                        $jeuxnombre[$k]['nf'] = $jeuxnombre[$k]['nf'] + 1;
                                        $jeuxnombre[$k]['montant'] = $jeuxnombre[$k]['montant'] + $montant;
                                    }
                                }
                            }

                            if (isset($fiche['option2'])) {

                                if ($boul1 == $chiffre) {
                                    $montant = $fiche['option2'];
                                    if ($z == 0) {
                                        $jeuxnombre[$i]['type']="Loto5op2";
                                        $jeuxnombre[$i]['boul'] = $boul1;
                                        $jeuxnombre[$i]['nf'] = $nombrefois + 1;
                                        $jeuxnombre[$i]['montant'] = $montant;
                                        $k = $i;
                                        $z = 1;
                                    } else {
                                        $jeuxnombre[$k]['nf'] = $jeuxnombre[$k]['nf'] + 1;
                                        $jeuxnombre[$k]['montant'] = $jeuxnombre[$k]['montant'] + $montant;
                                    }
                                }
                            }

                            if (isset($fiche['option3'])) {

                                if ($boul1 == $chiffre) {
                                    $montant = $fiche['option3'];
                                    if ($z == 0) {
                                        $jeuxnombre[$i]['type']="Loto5op3";
                                        $jeuxnombre[$i]['boul'] = $boul1;
                                        $jeuxnombre[$i]['nf'] = $nombrefois + 1;
                                        $jeuxnombre[$i]['montant'] = $montant;
                                        $k = $i;
                                        $z = 1;
                                    } else {
                                        $jeuxnombre[$k]['nf'] = $jeuxnombre[$k]['nf'] + 1;
                                        $jeuxnombre[$k]['montant'] = $jeuxnombre[$k]['montant'] + $montant;
                                    }
                                }
                            }
                        }
                        $i++;
                    }
                    break;

                default:
                    "";
                    break;
            }
        }
        return $jeuxnombre;
    }
}
