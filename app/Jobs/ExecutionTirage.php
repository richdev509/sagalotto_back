<?php

namespace App\Jobs;

use App\Http\Controllers\executeTirageAuto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\company;
use App\Models\user;
use App\Models\RulesOne;
use App\Models\RulesTwo;
use App\Models\BoulGagnant;
use App\Models\TicketVendu;
use App\Models\ticket_code;
use App\Models\tirage_record;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\maryajgratis;
use App\Models\monitor;
use App\Models\tirage;
use App\Models\rules_vendeur;
use Exception;


class ExecutionTirage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public  $totalGains = 0;
    public $totalgains = [];
    public $totalMontantJouer = 0;
    public $gagnantsData = [];
    public $gagnantsDataParCode = [];
    public $havegain = 0;
    public $havegainmaryaj = 0;
    public $havegainloto3 = 0;
    public $havegainloto4 = 0;
    public $havegainloto5 = 0;
    public $montantparid = [];
    protected $tiragename = "";
    public $havegainmaryajGratis = 0;
    protected $compagnie = "";
    protected $date_ = "";
    public $idmonitoring = "";
    public $itraiter = 0;
    public $totalfiche = "";
    public $tirageid = "";
    public $compagnieId = "";
    public $sesyon = "";
    /**
     * Create a new job instance.
     */
    public function __construct($tirage, $compagnies, $date, $sesyon)
    {
        $this->tiragename = $tirage;
        $this->date_ = $date;
        $this->compagnie = $compagnies;
        $this->sesyon = $sesyon;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {


            $this->execute($this->tiragename, $this->compagnie, $this->date_);
        } catch (\Exception $e) {
            throw $e;
        }
    }







    public function execute($tirage, $compagnies, $date)
    {
        $id = $compagnies;
        $totalGains = 0;
        $compagnieId = $id;


        $tirageName = $tirage;

        // Récupération des numéros gagnants pour le tirage spécifique
        $formattedDate = $date;
        $gagnants = BoulGagnant::where('compagnie_id', $compagnieId)
            ->where('tirage_id', $tirageName)
            ->whereDate('created_', $formattedDate)
            ->first();
        // First get the codes
        // $codes = Ticket_code::where('compagnie_id', $compagnieId)
        //     ->whereBetween('created_at', [$formattedDate . ' 00:00:00', $formattedDate . ' 23:59:59'])
        //     ->pluck('code');

        // Récupérer les tickets vendus en une seule requête
        $fiches = TicketVendu::whereHas('ticketCode', function ($query) use ($compagnieId, $formattedDate) {
            $query->where('compagnie_id', $compagnieId)
                ->whereBetween('created_at', [$formattedDate . ' 00:00:00', $formattedDate . ' 23:59:59']);
        })
            ->where('tirage_record_id', $tirageName)
            ->get();

        // Parcours des fiches
        $ficheDatas = "";
        $i = 1;
        if ($fiches != "") {
             $rules = RulesOne::where('compagnie_id', $compagnieId)->get();
             $rules2 = maryajgratis::where('compagnie_id', $compagnieId)->get();

            $this->compagnieId = $compagnieId;
            $this->totalfiche = $fiches->count();
            $kl = 0;

            $query = monitor::create([
                'userid' => $this->sesyon,
                'compagnieid' => $compagnieId,
                'totalfiche' => $this->totalfiche,
                'tirage_id' => $tirage,
            ]);

            $codemonitor = $query->id;
            foreach ($fiches as $fiche) {
                  $numerobranch = $fiche->ticketcode->branch_id;
                //get id vendeur
                $vendeur_id = $fiche->ticketcode->user_id;
                //check if the user record in rules_vendeur
                $rules_vendeur = rules_vendeur::where([
                    ['compagnie_id', '=', $compagnieId],
                    ['user_id', '=', $vendeur_id],
                ])->first();
                if ($rules_vendeur) {
                    $borletePrice = $rules_vendeur;
                    $maryajgratis = $rules_vendeur->prix_maryaj_gratis;
                }else{
                    //$borletePrice = 50;
                    // Trouver la règle correspondant au branch_id
                    $rule = $rules->firstWhere('branch_id', $numerobranch);
                    $rule2 = $rules2->firstWhere('branch_id', $numerobranch);
                    // Vérifier si la règle a été trouvée
                    if ($rule) {
                        // Obtenir le prix à partir de la règle
                        $borletePrice = $rule;
                    } else {
                        $borletePrice = (object) [
                            'prix' => 50,
                            'prix_second' => 20,
                            'prix_third' => 10,
                            'prix_maryaj' => 1000,
                            'prix_loto3' => 500,
                            'prix_loto4' => 5000,
                            'prix_loto5' => 25000,
                            'prix_gabel1' => 20,
                            'prix_gabel2' => 10,
                            'gabel_statut' => 0

                        ];
                    }
                    if ($rule2) {
                        $maryajgratis = $rule2->prix;
                    } else {
                        $maryajgratis = 0;
                    }
                }



                $ficheData = json_decode($fiche->boule, true);

                $ficheDatas = $ficheData;
                //recuperation de l'id du fiche
                $codeSpecifique = $fiche->ticket_code_id;

                //apel des function.
                try {
                    $reponse = $this->bolete($gagnants, $ficheData, $borletePrice->prix, $borletePrice->prix_second, $borletePrice->prix_third);

                    $reponse = $this->maryaj($gagnants, $ficheData, $borletePrice->prix_maryaj);

                    $this->loto3($gagnants, $ficheData, $borletePrice->prix_loto3);

                    $this->loto4($gagnants, $ficheData, $borletePrice->prix_loto4);

                    $this->loto5($gagnants, $ficheData, $borletePrice->prix_loto5);

                    $this->mariagegratis($gagnants, $ficheData, $maryajgratis);
                    if ($borletePrice->gabel_statut == 1) {
                        $this->gabel($gagnants, $ficheData, $borletePrice->prix_gabel1, $borletePrice->prix_gabel2);
                    }
                } catch (Exception $e) {
                    error_log($e->getMessage());
                }


                $this->totalgains[$i][] = $this->totalGains;
                $this->totalgains[$i][] = $codeSpecifique;

                if ($this->havegain == 1 || $this->havegainmaryaj == 1 || $this->havegainloto3 == 1 || $this->havegainloto4 == 1 || $this->havegainloto5 == 1 || $this->havegainmaryajGratis == 1) {



                    $reponseRequette = TicketVendu::where('ticket_code_id', $codeSpecifique)->where('tirage_record_id', $tirage)
                        ->update([
                            'winning' => $this->totalGains,
                            'is_win' => 1,
                            'is_calculated' => 1,
                        ]);

                    $kl = $kl + 1;
                    $reponse = monitor::where('id', $codemonitor)->update([
                        'totalexecuter' => $kl,
                    ]);

                    $this->totalGains = 0;
                    $this->havegain = 0;
                    $this->havegainmaryaj = 0;
                    $this->havegainloto3 = 0;
                    $this->havegainloto4 = 0;
                    $this->havegainloto5 = 0;
                    $this->havegainmaryajGratis = 0;
                } else {
                    $reponseRequette = TicketVendu::where('ticket_code_id', $codeSpecifique)->where('tirage_record_id', $tirage)
                        ->update([
                            'is_calculated' => 1,

                        ]);
                    $kl = $kl + 1;
                    $reponse = monitor::where('id', $codemonitor)->update([
                        'totalexecuter' => $kl,
                    ]);
                }
                $i = $i + 1;
            }
        }
    }


    public  function bolete($gagnants, $ficheData, $borlete_first, $borlete_second, $borlete_third)
    {
        if (isset($ficheData[0]['bolete']) && !empty($ficheData[0]['bolete'])) {
            // Accès à bolete dans le premier élément du tableau $ficheData
            $boleteData = $ficheData[0]['bolete'];
            $bo = "ok2";
            $i = 1;
            $y = 1;
            $is_one = 0;
            $is_two = 0;
            $is_tree = 0;
            foreach ($boleteData as $boul) {
                foreach ($boul as $cle => $valeur) {
                    if (substr($cle, 0, 4) === 'boul') {
                        $boulGagnante = $valeur;
                        $bo = $boulGagnante;

                        if ($boulGagnante == $gagnants->premierchiffre) {
                            $montantGagne = intval($boul['montant']) * intval($borlete_first);
                            $this->totalGains = $this->totalGains + $montantGagne;
                            $is_one = 1;
                        }
                        // Vérification si la boul est gagnante
                        if ($boulGagnante == $gagnants->secondchiffre) {
                            $montantGagne = intval($boul['montant']) * intval($borlete_second);
                            $this->totalGains = $this->totalGains + $montantGagne;
                            $is_two = 1;
                        }
                        if ($boulGagnante == $gagnants->troisiemechiffre) {
                            $montantGagne = intval($boul['montant']) * intval($borlete_third);
                            $this->totalGains = $this->totalGains + $montantGagne;
                            $is_tree = 1;
                        }

                        if ($is_one == 1 || $is_two == 1 || $is_tree == 1) {
                            $this->havegain = 1;
                            $i = $i + 1;
                        } else {
                        }
                    }
                }
            }
        } else {
            $this->havegain = 0;
        }
    }

    public  function maryaj($gagnants, $ficheData, $maryajPrice)
    {
        $do = "ok";
        if (isset($ficheData[1]['maryaj']) && !empty($ficheData[1]['maryaj'])) {
            $maryajData = $ficheData[1]['maryaj'];

            foreach ($maryajData as $fiche) {
                $do = $fiche['boul2'];
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
                    $montantGagne = intval($fiche['montant']) * intval($maryajPrice);
                    $this->totalGains = $this->totalGains + $montantGagne;
                    $this->havegainmaryaj = 1;
                } else {
                }
            }
        } else {
            $this->havegainmaryaj = 0;
        }
    }

    public function loto3($gagnants, $ficheData, $loto3Price)
    {
        if (isset($ficheData[2]['loto3']) && !empty($ficheData[2]['loto3'])) {
            $loto3Data = $ficheData[2]['loto3'];
            foreach ($loto3Data as $fiche) {
                $boul1 = $fiche['boul1'];
                $combinaisonGagnante = $gagnants->unchiffre . $gagnants->premierchiffre;

                if ($boul1 == $combinaisonGagnante) {
                    $montantGagne = intval($fiche['montant']) * intval($loto3Price);
                    $this->totalGains = $this->totalGains + $montantGagne;
                    $this->havegainloto3 = 1;
                } else {
                }
            }
        } else {
            $this->havegainloto3 = 0;
        }
    }


    public function loto4($gagnants, $ficheData, $loto4Price)
    {
        if (isset($ficheData[3]['loto4']) && !empty($ficheData[3]['loto4'])) {
            $loto4Data = $ficheData[3]['loto4'];
            $firsttest = 0;
            $options = [];
            foreach ($loto4Data as $fiche) {
                $boul1 = $fiche['boul1'];
                if (isset($fiche['option1'])) {
                    $combinaisonGagnante = $gagnants->secondchiffre . $gagnants->troisiemechiffre;
                    if ($boul1 == $combinaisonGagnante) {
                        $montantGagne = intval($fiche['option1']) * intval($loto4Price);
                        $this->totalGains = $this->totalGains + $montantGagne;
                        $firsttest = 1;
                        $this->havegainloto4 = 1;
                    } else {
                    }
                }
                if (isset($fiche['option2'])) {
                    $combinaisonGagnante2 = $gagnants->secondchiffre . $gagnants->premierchiffre;
                    if ($boul1 == $combinaisonGagnante2) {
                        $montantGagne = intval($fiche['option2']) * intval($loto4Price);
                        $this->totalGains = $this->totalGains + $montantGagne;
                        $firsttest = 1;
                        $this->havegainloto4 = 1;
                    }
                }
                if (isset($fiche['option3'])) {
                    $combinaisonGagnante3 = $gagnants->premierchiffre . $gagnants->troisiemechiffre;
                    if ($boul1 == $combinaisonGagnante3) {
                        $montantGagne = intval($fiche['option3']) * intval($loto4Price);
                        $this->totalGains = $this->totalGains + $montantGagne;
                        $firsttest = 1;
                        $this->havegainloto4 = 1;
                    }
                }
            }
        } else {
            $this->havegainloto4 = 0;
        }
    }


    public function loto5($gagnants, $ficheData, $loto5Price)
    {
        $do = "ok";
        if (isset($ficheData[4]['loto5']) && !empty($ficheData[4]['loto5'])) {


            $loto5Data = $ficheData[4]['loto5'];
            $options = [];
            $firsttest = 0;

            foreach ($loto5Data as $fiche) {

                $boul1 = $fiche['boul1'];
                // $boul2=$fiche['boul2'];

                if (isset($fiche['option1'])) {


                    $combinaisonGagnante = $gagnants->unchiffre . $gagnants->premierchiffre . $gagnants->secondchiffre;

                    if ($boul1 == $combinaisonGagnante) {
                        // La concaténation de 3 chiffres dans boul1 et boul2 correspond à la combinaison gagnante, multiplier le montant par le prix de "loto5"
                        $montantGagne = intval($fiche['option1']) * intval($loto5Price);
                        $this->totalGains = $this->totalGains + $montantGagne;
                        $firsttest = 1;
                        $this->havegainloto5 = 1;
                    }
                }

                if (isset($fiche['option2']) && $boul1 == $gagnants->unchiffre . $gagnants->premierchiffre . $gagnants->troisiemechiffre) {
                    // L'option2 est présente et correspond à la combinaison gagnante, multiplier le montant par le prix de l'option2
                    $montantGagne = intval($fiche['option2']) * intval($loto5Price);
                    $this->totalGains = $this->totalGains + $montantGagne;
                    $this->havegainloto5 = 1;
                }
                if (isset($fiche['option3']) && $boul1  == substr($gagnants->premierchiffre, -1) . $gagnants->secondchiffre . $gagnants->troisiemechiffre) {
                    // L'option3 est présente et correspond à la combinaison gagnante, multiplier le montant par le prix de l'option3
                    $montantGagne = intval($fiche['option3']) * intval($loto5Price);
                    $this->totalGains = $this->totalGains + $montantGagne;

                    $this->havegainloto5 = 1;
                }
            }
        } else {
            $this->havegainloto5 = 0;
        }
    }


    public function mariagegratis($gagnants, $ficheData, $maryajgratis)
    {
        if (isset($ficheData[5]['mariage_gratis']) && !empty($ficheData[5]['mariage_gratis'])) {
            $maryajData = $ficheData[5]['mariage_gratis'];

            foreach ($maryajData as $fiche) {

                $boul1 = $fiche['boul1'];
                $boul2 = $fiche['boul2'];
                if (
                    ($boul1 == $gagnants->premierchiffre && $boul2 == $gagnants->secondchiffre) ||
                    ($boul1 == $gagnants->premierchiffre && $boul2 == $gagnants->troisiemechiffre) ||
                    ($boul1 == $gagnants->secondchiffre && $boul2 == $gagnants->premierchiffre) ||
                    ($boul1 == $gagnants->secondchiffre && $boul2 == $gagnants->troisiemechiffre) ||
                    ($boul1 == $gagnants->troisiemechiffre && $boul2 == $gagnants->premierchiffre) ||
                    ($boul1 == $gagnants->troisiemechiffre && $boul2 == $gagnants->secondchiffre)
                ) {
                    $montantGagne =  $maryajgratis;
                    $this->totalGains = $this->totalGains + $montantGagne;
                    $this->havegainmaryajGratis = 1;
                } else {
                }
            }
        } else {
            $this->havegainmaryajGratis = 0;
        }
    }
    public function gabel($gagnants, $ficheData, $gabelPrice1, $gabelPrice2)
    {
        if (isset($ficheData[2]['loto3']) && !empty($ficheData[2]['loto3'])) {
            $loto3Data = $ficheData[2]['loto3'];
            foreach ($loto3Data as $fiche) {
                $boul1 = $fiche['boul1'];

                // Vérification si le boul1 correspond à la combinaison de unchiffre et premierchiffre
                $combinaisonGagnante1 = $gagnants->unchiffre . substr($gagnants->premierchiffre, 0, 1);
                $combinaisonGagnante2 = $gagnants->premierchiffre;

                if (substr($boul1, 0, 2) == $combinaisonGagnante1) {
                    // Le boul1 correspond à la combinaison gagnante, multiplier le montant par le prix de "loto3"
                    $montantGagne = intval($fiche['montant']) * intval($gabelPrice1);
                    $this->totalGains = $this->totalGains + $montantGagne;

                    $this->havegainloto3 = 1;
                } elseif (substr($boul1, 1, 2) == $combinaisonGagnante2) {
                    $montantGagne = intval($fiche['montant']) * intval($gabelPrice2);
                    $this->totalGains = $this->totalGains + $montantGagne;

                    $this->havegainloto3 = 1;
                } else {
                }
            }
        } else {
            $this->havegainloto3 = 0;
        }
    }
}
