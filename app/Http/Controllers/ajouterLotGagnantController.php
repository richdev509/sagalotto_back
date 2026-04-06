<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoulGagnant;
use App\Http\Controllers\executeTirageController;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\tirage_record;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ajouterLotGagnantController extends Controller
{

    public function index(Request $request)
    {
        $query = BoulGagnant::where('compagnie_id', session('loginId'))
            ->with('tirage_record');

        // Filter by date range if provided
        if ($request->has('date_debut') && $request->has('date_fin')) {
            $dateDebut = $request->input('date_debut');
            $dateFin = $request->input('date_fin');
            
            if ($dateDebut && $dateFin) {
                $query->whereBetween('created_', [$dateDebut, $dateFin]);
            }
        }

        $list = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('list-lo', compact('list'));
    }

    public function exportPdf(Request $request)
    {
        // Increase memory limit temporarily for PDF generation
        ini_set('memory_limit', '256M');
        
        $query = BoulGagnant::where('compagnie_id', session('loginId'))
            ->with(['tirage_record' => function($q) {
                $q->select('id', 'name'); // Only load needed fields
            }])
            ->select('id', 'tirage_id', 'unchiffre', 'premierchiffre', 'secondchiffre', 'troisiemechiffre', 'etat', 'created_', 'created_at', 'compagnie_id');

        // Filter by date range if provided
        if ($request->has('date_debut') && $request->has('date_fin')) {
            $dateDebut = $request->input('date_debut');
            $dateFin = $request->input('date_fin');
            
            if ($dateDebut && $dateFin) {
                $query->whereBetween('created_', [$dateDebut, $dateFin]);
            }
        }

        // Limit results to prevent memory issues (max 500 records)
        $list = $query->orderBy('created_at', 'desc')->limit(500)->get();
        
        $compagnie = \App\Models\company::select('id', 'name')->find(session('loginId'));
        
        $data = [
            'list' => $list,
            'dateDebut' => $request->input('date_debut'),
            'dateFin' => $request->input('date_fin'),
            'compagnie' => $compagnie,
            'dateExport' => Carbon::now()->format('d/m/Y H:i:s')
        ];
        
        $pdf = PDF::loadView('pdf.list-lo', $data);
        $pdf->setPaper('A4', 'portrait');
        
        // Additional options to reduce memory usage
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'defaultFont' => 'sans-serif',
            'isFontSubsettingEnabled' => true,
        ]);
        
        $filename = 'list-lo-' . Carbon::now()->format('Y-m-d-His') . '.pdf';
        
        return $pdf->download($filename);
    }
    public function loadMore(Request $request)
{
    if ($request->ajax()) {
        $list = BoulGagnant::where('compagnie_id', session('loginId'))->orderBy('created_at', 'desc')->paginate(10); // Paginer par 10 éléments, ajustez selon vos besoins
        $view= view('partials.list-items', compact('list'))->render();
        return response()->json(['html' => $view, 'hasMore' => $list->hasMorePages()]);
    }
}



    public function ajouterlo(Request $request)
    {
        $id = $request->id;
        $dat_ = $request->dat_;
        $list = tirage_record::where('compagnie_id', session('loginId'))->get();
        if ($id != "") {
            $record = BoulGagnant::where('compagnie_id', session('loginId'))->where('tirage_id', $id)->where('created_', $dat_)->first();
            return view('ajoutelo', compact('list', 'record'));
        }
        return view('ajoutelo', compact('list'));
    }


    public function store(Request $request)
    {


        $tirageId = $request->input('tirage');
        $date = $request->input('date');
        $unchiffre = $request->input('unchiffre');
        $premierchiffre = $request->input('premierchiffre');
        $secondchiffre = $request->input('secondchiffre');
        $troisiemechiffre = $request->input('troisiemechiffre');

        $erreurs = $this->validerEntrees($tirageId, $unchiffre, $premierchiffre, $secondchiffre, $troisiemechiffre);

        if ($erreurs) {
            $messagesErreur = implode(', ', $erreurs);
            notify()->error($messagesErreur);
            return redirect()->back();
        }
        if (strlen((string)$premierchiffre) == 1) {
        }
        //verifaction exist lo
        $resultExist = $this->ifExisteLo($tirageId, $date);
        if ($resultExist) {
            notify()->error('Lo sa existe deja');
            return redirect()->back();
        }


        //fin
        // Vérifier si l'heure du serveur est supérieure ou égale à $heureTirage
        $compagnieId = session('loginId');

        $heureTirage = tirage_record::where('id', $tirageId)->where('compagnie_id', $compagnieId)->value('hour');


        $heureServeur = Carbon::now()->format('H:i:s');



        if (Carbon::parse($heureServeur)->gte(Carbon::parse($heureTirage)) || $date < Carbon::now()->format('Y-m-d')) {
        } else {
            // Le tirage n'a pas encore commencé
            notify()->info('lo an poko ka mete. tiraj la ap femen a:' . $heureTirage);
            return redirect()->back();
        }

        $formattedDate = $date; //Carbon::createFromFormat('m-d-Y', $date)->format('Y-m-d');
        try {

            $reponseadd = BoulGagnant::create([
                'tirage_id' => $request->input('tirage'),
                'compagnie_id' => session('loginId'),
                'unchiffre' => $unchiffre,
                'premierchiffre' => $premierchiffre,
                'secondchiffre' => $secondchiffre,
                'troisiemechiffre' => $troisiemechiffre,
                'etat' => 'true',
                'created_' => $formattedDate
            ]);

            if ($reponseadd) {
                $class = new executeTirageController();
                $reponse = $class->verification($tirageId, $date);
                if ($reponse == '1') {
                    notify()->success('Lo ajout avek sikese');
                    return redirect()->back();
                } elseif ($reponse == '-1') {
                    notify()->error('Lo ajoute ,Pa gen fich ki jwe pou tiraj sa');
                    return redirect()->route('listlo');
                } elseif ($reponse == '0') {
                    notify()->error('Le pou tiraj fenmen poko rive');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            // Gérer l'exception si la création échoue
            notify()->error('erreur dajout');
            return redirect()->back();
        }
    }


    function ifExisteLo($id, $date)
    {
        $exist = BoulGagnant::where('compagnie_id', session('loginId'))->where('tirage_id', $id)->where('created_', $date)->get();
        if ($exist->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function validerEntrees($tirageId, $unchiffre, $premierchiffre, $secondchiffre, $troisiemechiffre)
    {


        $premierchiffre = (string) $premierchiffre;
        $secondchiffre = (string) $secondchiffre;
        $troisiemechiffre = (string) $troisiemechiffre;

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
                'premierchiffre' => 'required|string|size:2',
                'secondchiffre' => 'required|string|size:2',
                'troisiemechiffre' => 'required|string|size:2',
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


    public function modifierlo(Request $request)
    {
        $tirageId = $request->input('tirage');
        $date = $request->input('date');
        $unchiffre = $request->input('unchiffre');
        $premierchiffre = $request->input('premierchiffre');
        $secondchiffre = $request->input('secondchiffre');
        $troisiemechiffre = $request->input('troisiemechiffre');

        $erreurs = $this->validerEntrees($tirageId, $unchiffre, $premierchiffre, $secondchiffre, $troisiemechiffre);

        if ($erreurs) {
            $messagesErreur = implode(', ', $erreurs);
            notify()->error($messagesErreur);
            return redirect()->back();
        }

        //Espace pour effectuer l'appel du fonction pour reinitialiser les donees.
        $instance = new executeTirageController();
        $reponses = $instance->rentier($date, $tirageId);
        if ($reponses == false) {
            notify()->error('Pwoblem miz ajou kontakte sevis teknik');
            //dd($reponses);
            return redirect()->back();
        }

        $Boulgnant = BoulGagnant::where('compagnie_id', session('loginId'))->where('tirage_id', $tirageId)->where('created_', $date)->first();

        if ($Boulgnant) {
            $reponseadd = "";
            try {
                $reponseadd = $Boulgnant->update([
                    'unchiffre' => $unchiffre,
                    'premierchiffre' => $premierchiffre,
                    'secondchiffre' => $secondchiffre,
                    'troisiemechiffre' => $troisiemechiffre,
                    'etat' => 'true',
                ]);


                if ($reponseadd == true) {
                    $class = new executeTirageController();
                    $reponse = $class->verification($tirageId, $date);
                    if ($reponse == '1') {
                        notify()->success('Lo Modifier et triyaj sikese');
                        return redirect()->back();
                    } elseif ($reponse == '-1') {
                        notify()->error('Pa gen fich ki jwe pou tiraj sa');
                        return redirect()->back();
                    } elseif ($reponse == '0') {
                        notify()->error('Le pou tiraj fenmen poko rive');
                        return redirect()->back();
                    }
                }
                notify()->success('Lo Modifier e triyaj sikese');
                return redirect()->route('listlo');
            } catch (\Exception $e) {
                // Gérer l'exception si la création échoue
                notify()->error('erreur dajout la', $e->getMessage());
                return redirect()->back();
            }
        } else {
            notify()->error('Modification impossible');
            return redirect()->back();
        }
    }
}
