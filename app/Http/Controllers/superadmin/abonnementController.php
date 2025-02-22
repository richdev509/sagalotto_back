<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\company;
use App\Models\tbladmin;
use App\Models\User;
use App\Models\abonnementhistoriqueuser;
use App\Models\ticket_code;
use App\Http\Controllers\historiquetransaction;
use App\Models\historiquetransanction;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class abonnementController extends Controller
{


    public function viewhistorique()
    {
        if (session('role') == "admin" || session('role') == "comptable") {
            $data = abonnementhistoriqueuser::orderBy('created_at', 'desc')->get();
            return  view('superadmin.historiqueabonnement', compact('data'));
        }
    }
    public function viewFacture()
    {
        if (session('role') == "admin" || session('role') == "comptable") {
            $data = company::all();
            $facture = 0;
            return  view('superadmin.facture', compact('data', 'facture'));
        }
    }
    public function genererFacture(Request $request)
    {
        if (session('role') == "admin" || session('role') == "comptable" || session('role') == "admin2") {
            $validator = $request->validate([
               
                'company' => 'required',
                'date' => 'required',
            ]);
            $date = Carbon::create($request->date);
            $datee = $date;
            $date =$date->format('Y-m-d');
            $data = company::all();
            $compagnie =company::where('id', $request->company)->first();
            $facture = 1;
            $vendeur = ticket_code::where([
                ['created_at', '>=', $datee->subDays(5)],
                ['compagnie_id', '=', $compagnie->id]
    
            ])->distinct()
                ->pluck('user_id')
                ->count();
    
            return  view('superadmin.facturePay', compact('data', 'facture','compagnie','vendeur','date'));
        }
    }
    public function addabonement(Request $request)
    {


        if (session('role') == 'admin' || session('role') == "addeur" || session('role') == "comptable") {


            $reponse = Company::where('code', $request->code)->first();

            if ($reponse) {
                $retour = $this->getDaysRemaining($reponse->dateplan, $reponse->dateexpiration);
                //$datedebutplan = Carbon::parse($request->date);
                //$datedebutplanI = Carbon::parse($request->date);

                $datedebutplan = Carbon::parse($reponse->dateexpiration);
                $dureemois = $request->duree;
                $dateplan = Carbon::parse($reponse->dateplan);
                $dateplan = $dateplan->format('Y-m-d');


                $dateExpirations = Carbon::parse($reponse->dateexpiration);
                $dateExpirationS = Carbon::parse($reponse->dateexpiration);
                $nouvelleDate = $request->date ? Carbon::parse($request->date) : null;
                $nouvelleDate2 = $request->date ? Carbon::parse($request->date) : null;


                if (!$nouvelleDate) {
                    $dateexpiration = $dateExpirations->addMonths($dureemois);
                    $datedebut = $dateExpirationS->addDays(1);
                } else {
                    if ($nouvelleDate->lessThan($dateExpirationS)) {
                        notify()->error('La nouvelle date est inférieure à la date d\'expiration.');
                        return redirect()->route('listecompagnie');
                    }
                    $dateexpiration = $nouvelleDate->addMonths($dureemois);
                    $datedebut = $nouvelleDate2;
                }



                // Mettre à jour l'abonnement dans la base de données



                $nb = $this->calculnombreuser($reponse->id, $dateplan, $dateExpirations->format('Y-m-d'));
                $summ = $this->calculbalance($reponse->id, $dateplan);

                $montantdisponible = $this->findmontant($reponse->id, $dateplan);

                if ($summ && $summ > 0) {
                    notify()->error('Une balance doit être acquittée');
                    return redirect()->route('historiquesaabonnement')->with('error', 'La balance de :' . $reponse->name . ' :doit être acquittée');
                }
                $balance = 0;
                $etat = null;
                if ($nb == 0) {
                    $nombrepos = 0;
                } else {
                    $nombrepos = $nb;

                    $montantdue = $nombrepos * $reponse->plan;
                    $montantdisponible = $this->findmontant($reponse->id, $dateplan);
                    if ($montantdue > $montantdisponible) {
                        $calculbalance = $montantdue - $montantdisponible;
                        $balance = $calculbalance;
                        $etat = "dwe";
                    }
                }
                $reponse->update([
                    'dateplan' => $datedebut->format('Y-m-d'),
                    'dateexpiration' => $dateexpiration->format('Y-m-d'),
                    'is_block' => '0',
                ]);

                $query = abonnementhistoriqueuser::insertGetId([
                    'idcompagnie' => $reponse->id,
                    'iduser' => session('id'),
                    'nombremois' => $request->duree,
                    'nombrepos' => $nombrepos,
                    'montant' => $request->montant,
                    'balance' => $balance,
                    'date' => Carbon::now()->format('Y-m-d'),
                    'dateabonnement' => $datedebut->format('Y-m-d'),
                    'action' => 'Ajoute Abonnement',
                    'created_at' => Carbon::now(),
                    'etat' => $etat,
                ]);
                $libelle = 'Paiement addabonement';
                $query2 = historiquetransanction::insertGetId(
                    [
                        'iduser' => session('id'),
                        'idCompagnie' => $reponse->id,
                        'montant' => $request->montant,
                        'libelle' => $libelle,
                        'idabonnement' => $query,

                    ]
                );

                notify()->success('Abonnement mis à jour avec succès');
                return redirect()->route('listecompagnie');
            } else {
                notify()->error('Compagnie non trouvée');
                return redirect()->route('listecompagnie');
            }
        } else {
            notify()->error('Vous n\'avez pas access a niveau');
            return redirect()->route('listecompagnie');
        }
    }

    public function paiementdwe(Request $request)
    {

        try {
            // Récupérer l'enregistrement correspondant
            $reponse = abonnementhistoriqueuser::where('id', $request->id)->first();
            if ($reponse->balance != $request->montant) {
                notify()->error('Une erreur est survenue lors du traitement du paiement. Veuillez réessayer.');
                return redirect()->route('historiquesaabonnement')->with('error', 'Une erreur est survenue montant inferieure au montant due. Veuillez réessayer.');
            }
            // Mettre à jour l'enregistrement
            $reponse->update([
                'montant' => $reponse->montant + $request->montant,
                'etat' => 'ok',
                'balance' => 0,
            ]);

            // Insérer une nouvelle transaction dans historiquetransanction
            $query2 = historiquetransanction::insertGetId([
                'iduser' => session('id'),
                'idCompagnie' => $reponse->idcompagnie,
                'montant' => $request->montant,
                'libelle' => 'paiement POS supplement du mois:' . $reponse->dateabonnement . '',
                'idabonnement' => $reponse->id,
            ]);

            // Afficher une notification de succès
            notify()->success('Paiement ajouté avec succès');
            return redirect()->route('historiquesaabonnement');
        } catch (\Exception $e) {
            // Afficher une notification d'erreur
            notify()->error('Une erreur est survenue lors du traitement du paiement. Veuillez réessayer.');
            return redirect()->route('historiquesaabonnement')->with('error', 'Une erreur est survenue lors du traitement du paiement. Veuillez réessayer.');
        }
    }




    function getDaysRemaining($dateplan, $datefin)
    {

        $startDate = Carbon::parse($dateplan);
        $endDate = Carbon::parse($datefin);
        $currentDate = Carbon::now();

        // Vérifier si l'abonnement est encore valide
        if ($currentDate->between($startDate, $endDate)) {
            $daysRemaining = $currentDate->diffInDays($endDate);
            return  $daysRemaining;
        } else {
            return $d = 0;
        }
    }
    public function calculnombreuser($compagnieid, $datedebut, $datefin)
    {
        $userCount = null;

        $userCount = DB::table('ticket_code')
            ->where('compagnie_id', $compagnieid)
            ->whereBetween('created_at', [$datedebut,  $datefin])
            ->distinct('user_id')
            ->count('user_id');
        if (!$userCount) {
            $userCount = 0;
        }
        return $userCount;
    }


    public function calculbalance($compagnieid, $date)
    {

        $result = abonnementhistoriqueUser::where('idcompagnie', $compagnieid)
            ->where('etat', 'dwe') // Assurez-vous que 'dwe' correspond à la valeur attendue dans la base de données
            ->where('dateabonnement', $date)
            ->where('balance', '>', 0)
            ->value('balance');

        return $result;
    }

    public function findmontant($compagnieid, $date)
    {
        $result = abonnementhistoriqueuser::where('idcompagnie', $compagnieid)->where('dateabonnement', $date)->where('balance', 0)->value('montant');
        return $result;
    }
}
