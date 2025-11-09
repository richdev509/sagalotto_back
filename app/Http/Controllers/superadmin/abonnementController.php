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
use App\Models\facture;
use App\Http\Controllers\historiquetransaction;
use App\Models\historiquetransanction;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;

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
            // Validate the request
            $validator = $request->validate([
                'company' => 'required',
                'date' => 'required',
            ]);

            // Parse the input date using Carbon
            $date = Carbon::create($request->date);
            $datee = $date->copy(); // Use a copy to avoid mutating the original object
            $date = $date->format('Y-m-d');

            // Fetch all companies
            $data = company::all();
            $facture = 1;
            // Fetch the selected company
            $compagnie = company::where('id', $request->company)->first();
            //check if facture exists this month
            $existsThisMonth = facture::where('compagnie_id', $compagnie->id)
                ->whereYear('due_date', $datee->year)
                ->whereMonth('due_date', $datee->month)
                ->exists();
            if ($existsThisMonth) {

                $fac = Facture::where('compagnie_id', $compagnie->id)
                    ->whereYear('due_date', $datee->year)
                    ->whereMonth('due_date', $datee->month)
                    ->first();
                $plan = $fac->plan;
                $vendeur = $fac->number_pos;
                $facture_id = $fac->id;
                return view('superadmin.facturePay', compact('data', 'plan', 'facture', 'compagnie', 'vendeur', 'date', 'facture_id'));
            }
            // Get the plan (if needed)
            $plan = $compagnie->plan;

            // Define the date range for the query
            $startDate = $datee->copy()->subDays(6)->startOfDay(); // Start of the day, 6 days before
            $endDate = $datee->copy()->subDays(1)->endOfDay(); // End of the day, 1 day before

            // Fetch distinct user IDs for tickets created within the date range
            $vendeur = ticket_code::where([
                ['created_at', '>=', $startDate],
                ['created_at', '<=', $endDate],
                ['compagnie_id', '=', $compagnie->id]
            ])->distinct()
                ->pluck('user_id')
                ->count();
            $nfac = new Facture();
            $nfac->compagnie_id = $compagnie->id;
            $nfac->amount = $plan * $vendeur; // Example value - adjust as needed
            $nfac->plan = $plan;
            $nfac->number_pos = $vendeur;
            $nfac->paid_amount = 0; // Example value
            $nfac->due_date = $datee;
            $nfac->is_paid = 0;
            $nfac->month_added = 0;
            $nfac->paid_at = null;
            $nfac->payment_method = null;
            $nfac->payment_id = null;
            $nfac->currency = null;
            $nfac->description = 'Facture du ' . $date;
            // Render the existing Blade view 'superadmin/facturePay' to HTML
            // The view expects: $compagnie, $vendeur, $date
            $html = View::make('superadmin.facturePay', [
                'compagnie' => $compagnie,
                'vendeur'   => $vendeur,
                'date'      => $date,
            ])->render();

            // Ensure the output directory exists under public
            File::ensureDirectoryExists(public_path('factures'));

            // Build a strong, unique filename under public/factures
            $slug = Str::slug($compagnie->name ?? 'company', '-');
            $datePart = $datee->format('Ymd');
            $random = Str::random(12);
            $relativePath = "factures/{$slug}-{$compagnie->id}-{$datePart}-{$random}.png";
            $absolutePath = public_path($relativePath);

            // Prefer wkhtmltoimage; fallback to Browsershot if unavailable
            $rendered = false;
            $wk = env('WKHTMLTOIMAGE_PATH');
            if ($wk && file_exists($wk)) {
                try {
                    \Illuminate\Support\Facades\File::ensureDirectoryExists(storage_path('app/tmp'));
                    $tmpHtml = storage_path('app/tmp/facture_'.uniqid().'.html');
                    file_put_contents($tmpHtml, $html);
                    $cmd = escapeshellcmd($wk)
                        . ' --width 900 --quality 92 --enable-local-file-access '
                        . escapeshellarg($tmpHtml) . ' '
                        . escapeshellarg($absolutePath);
                    $out = [];
                    $rc = 0;
                    exec($cmd.' 2>&1', $out, $rc);
                    logger()->info('wkhtmltoimage output (genererFacture)', ['rc'=>$rc,'out'=>implode("\n", $out)]);
                    @unlink($tmpHtml);
                    if ($rc === 0 && file_exists($absolutePath)) {
                        $nfac->facture_image = $relativePath;
                        $rendered = true;
                    }
                } catch (\Throwable $e2) {
                    logger()->error('wkhtmltoimage primary failed (genererFacture)', ['error' => $e2->getMessage()]);
                }
            }

            if (!$rendered) {
                try {
                    $bs = Browsershot::html($html)
                        ->windowSize(900, 1200)
                        ->deviceScaleFactor(2)
                        ->waitUntilNetworkIdle()
                        ->select('#table-container');

                    if ($node = env('BROWSERSHOT_NODE_PATH')) {
                        $bs->setNodeBinary($node);
                    }
                    if ($npm = env('BROWSERSHOT_NPM_PATH')) {
                        $bs->setNpmBinary($npm);
                    }
                    $exec = env('PUPPETEER_EXECUTABLE_PATH') ?: env('BROWSERSHOT_CHROME_PATH');
                    if ($exec) {
                        if (method_exists($bs, 'setChromePath')) {
                            $bs->setChromePath($exec);
                        } elseif (method_exists($bs, 'setChromiumPath')) {
                            $bs->setChromiumPath($exec);
                        }
                    }
                    if (env('BROWSERSHOT_NO_SANDBOX', true)) {
                        $bs->setOption('args', ['--no-sandbox','--disable-setuid-sandbox','--disable-dev-shm-usage']);
                    }

                    $bs->save($absolutePath);
                    $nfac->facture_image = $relativePath;
                    $rendered = true;
                } catch (\Throwable $e) {
                    logger()->error('Browsershot fallback failed in controller', ['error' => $e->getMessage()]);
                }
            }

            if (!$rendered) {
                $nfac->facture_image = '';
            }

            // persist the facture and prepare the id for the view
            $nfac->save();
            $facture_id = $nfac->id;

            // Pass data to the view
            return view('superadmin.facturePay', compact('data', 'plan', 'facture', 'compagnie', 'vendeur', 'date', 'facture_id'));
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

    public function payerfacture(Request $request)
    {
        if (!(session('role') == "admin" || session('role') == "comptable" || session('role') == "admin2")) {
            notify()->error('Accès refusé');
            return redirect()->route('facture');
        }

        $validated = $request->validate([
            'facture_id' => 'required|exists:factures,id',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:50',
            'payment_id' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $facture = Facture::find($validated['facture_id']);
        if (!$facture) {
            notify()->error('Facture introuvable');
            return back();
        }
        $wasPaid = (bool) ($facture->is_paid ?? false);

        // Incremental payment handling (auto status). Cap at total amount
        $newPaid = ($facture->paid_amount ?? 0) + (float)$validated['paid_amount'];
        if ($facture->amount !== null) {
            $newPaid = min($newPaid, (float)$facture->amount);
        }
        $facture->paid_amount = $newPaid;
        $facture->payment_method = $validated['payment_method'];
        $facture->payment_id = $validated['payment_id'] ?? $facture->payment_id;
        if (!empty($validated['description'])) {
            // Append description entries with timestamp
            $prefix = now()->format('Y-m-d H:i') . ': ';
            $facture->description = trim(($facture->description ? $facture->description . "\n" : '') . $prefix . $validated['description']);
        }
        // Auto compute status based on totals
        if ($facture->amount !== null && $facture->paid_amount >= $facture->amount) {
            $facture->is_paid = 1;
            if (!$facture->paid_at) {
                $facture->paid_at = now();
            }
        } else {
            $facture->is_paid = 0;
        }

        $facture->save();

        // If newly fully paid, extend the company's subscription by one month
        // Use month_added guard to avoid duplicate extension
        if (!$wasPaid && ($facture->month_added ?? 0) == 0 && $facture->amount !== null && $facture->paid_amount >= $facture->amount) {
            $comp = company::find($facture->compagnie_id);
            if ($comp) {
                // Always extend from current expiration date, not from current date
                $base = $comp->dateexpiration ? Carbon::parse($comp->dateexpiration) : Carbon::today();
                $comp->dateexpiration = $base->copy()->addMonth()->format('Y-m-d');
                $comp->save();
                // Mark facture as processed for month addition
                $facture->month_added = 1;
                $facture->save();
            }
        }

        notify()->success('Paiement enregistré');
        return redirect()->route('facture', ['#hist-section']);
    }

    public function regenerateFactureImage(Request $request)
    {
        if (!(session('role') == "admin" || session('role') == "comptable" || session('role') == "admin2")) {
            notify()->error('Accès refusé');
            return redirect()->route('facture');
        }

        $validated = $request->validate([
            'facture_id' => 'required|exists:factures,id',
        ]);

        $facture = Facture::find($validated['facture_id']);
        if (!$facture) {
            notify()->error('Facture introuvable');
            return back();
        }

        $compagnie = company::find($facture->compagnie_id);
        if (!$compagnie) {
            notify()->error('Compagnie introuvable');
            return back();
        }

        // Prepare data for the facture view
        $vendeur = $facture->number_pos ?? 0;
        $datee = Carbon::parse($facture->due_date);
        $date = $datee->format('Y-m-d');

        $html = View::make('superadmin.facturePay', [
            'compagnie' => $compagnie,
            'vendeur'   => $vendeur,
            'date'      => $date,
        ])->render();

        // Ensure directory exists
        File::ensureDirectoryExists(public_path('factures'));

        // Build new strong filename
        $slug = Str::slug($compagnie->name ?? 'company', '-');
        $datePart = $datee->format('Ymd');
        $random = Str::random(12);
        $relativePath = "factures/{$slug}-{$compagnie->id}-{$datePart}-{$random}.png";
        $absolutePath = public_path($relativePath);

        // Prefer wkhtmltoimage first, then fallback to Browsershot
        $oldRelative = $facture->facture_image ?? null;
        $oldAbsolute = $oldRelative ? public_path($oldRelative) : null;

        $wk = env('WKHTMLTOIMAGE_PATH');
        if ($wk && file_exists($wk)) {
            try {
                \Illuminate\Support\Facades\File::ensureDirectoryExists(storage_path('app/tmp'));
                $tmpHtml = storage_path('app/tmp/facture_'.uniqid().'.html');
                file_put_contents($tmpHtml, $html);
                $out = [];
                $rc = 0;
                $cmd = escapeshellcmd($wk)
                    . ' --width 900 --quality 92 --enable-local-file-access '
                    . escapeshellarg($tmpHtml) . ' '
                    . escapeshellarg($absolutePath);
                exec($cmd.' 2>&1', $out, $rc);
                logger()->info('wkhtmltoimage output (regenerate)', ['rc'=>$rc,'out'=>implode("\n", $out)]);
                @unlink($tmpHtml);
                if ($rc === 0 && file_exists($absolutePath)) {
                    $facture->facture_image = $relativePath;
                    $facture->save();
                    if ($oldAbsolute && File::exists($oldAbsolute)) {
                        @unlink($oldAbsolute);
                    }
                    notify()->success('Image de la facture regénérée');
                    return back();
                }
            } catch (\Throwable $e2) {
                logger()->error('wkhtmltoimage primary failed (regenerate)', ['error' => $e2->getMessage(), 'facture_id' => $facture->id]);
            }
        }

        try {
            $bs = Browsershot::html($html)
                ->windowSize(900, 1200)
                ->deviceScaleFactor(2)
                ->waitUntilNetworkIdle()
                ->select('#table-container');

            if ($node = env('BROWSERSHOT_NODE_PATH')) {
                $bs->setNodeBinary($node);
            }
            if ($npm = env('BROWSERSHOT_NPM_PATH')) {
                $bs->setNpmBinary($npm);
            }
            $exec = env('PUPPETEER_EXECUTABLE_PATH') ?: env('BROWSERSHOT_CHROME_PATH');
            if ($exec) {
                if (method_exists($bs, 'setChromePath')) {
                    $bs->setChromePath($exec);
                } elseif (method_exists($bs, 'setChromiumPath')) {
                    $bs->setChromiumPath($exec);
                }
            }
            if (env('BROWSERSHOT_NO_SANDBOX', true)) {
                $bs->setOption('args', ['--no-sandbox','--disable-setuid-sandbox','--disable-dev-shm-usage']);
            }

            $bs->save($absolutePath);

            // Update the facture record with the new image path
            $facture->facture_image = $relativePath;
            $facture->save();

            // Optionally clean up the old image if it existed
            if ($oldAbsolute && File::exists($oldAbsolute)) {
                @unlink($oldAbsolute);
            }

            notify()->success('Image de la facture regénérée');
        } catch (\Throwable $e) {
            logger()->error('Browsershot regenerate failed', ['error' => $e->getMessage(), 'facture_id' => $facture->id]);
            notify()->error("Echec de regénération de l'image de la facture");
        }

        return back();
    }

    public function regenerateAndShowFacture($id)
    {
        if (!(session('role') == "admin" || session('role') == "comptable" || session('role') == "admin2")) {
            notify()->error('Accès refusé');
            return redirect()->route('facture');
        }

        $facture = Facture::find($id);
        if (!$facture) {
            notify()->error('Facture introuvable');
            return redirect()->route('facture');
        }
        $compagnie = company::find($facture->compagnie_id);
        if (!$compagnie) {
            notify()->error('Compagnie introuvable');
            return redirect()->route('facture');
        }

        $vendeur = $facture->number_pos ?? 0;
        $datee = Carbon::parse($facture->due_date);
        $date = $datee->format('Y-m-d');

        // HTML used for image capture
        $html = View::make('superadmin.facturePay', [
            'compagnie' => $compagnie,
            'vendeur'   => $vendeur,
            'date'      => $date,
        ])->render();

        File::ensureDirectoryExists(public_path('factures'));
        $slug = Str::slug($compagnie->name ?? 'company', '-');
        $datePart = $datee->format('Ymd');
        $random = Str::random(12);
        $relativePath = "factures/{$slug}-{$compagnie->id}-{$datePart}-{$random}.png";
        $absolutePath = public_path($relativePath);

        // Try wkhtmltoimage first, then fallback to Browsershot
        $oldRelative = $facture->facture_image ?? null;
        $oldAbsolute = $oldRelative ? public_path($oldRelative) : null;
        $done = false;

        $wk = env('WKHTMLTOIMAGE_PATH');
        if ($wk && file_exists($wk)) {
            try {
                \Illuminate\Support\Facades\File::ensureDirectoryExists(storage_path('app/tmp'));
                $tmpHtml = storage_path('app/tmp/facture_'.uniqid().'.html');
                file_put_contents($tmpHtml, $html);
                $out = [];
                $rc = 0;
                $cmd = escapeshellcmd($wk)
                    . ' --width 900 --quality 92 --enable-local-file-access '
                    . escapeshellarg($tmpHtml) . ' '
                    . escapeshellarg($absolutePath);
                exec($cmd.' 2>&1', $out, $rc);
                logger()->info('wkhtmltoimage output (regenerate-show)', ['rc'=>$rc,'out'=>implode("\n", $out)]);
                @unlink($tmpHtml);
                if ($rc === 0 && file_exists($absolutePath)) {
                    $facture->facture_image = $relativePath;
                    $facture->save();
                    if ($oldAbsolute && File::exists($oldAbsolute)) {
                        @unlink($oldAbsolute);
                    }
                    $done = true;
                }
            } catch (\Throwable $e) {
                logger()->error('wkhtmltoimage failed (regenerate-show)', ['error' => $e->getMessage(), 'facture_id' => $facture->id]);
            }
        }

        if (!$done) {
            try {
                $bs = Browsershot::html($html)
                    ->windowSize(900, 1200)
                    ->deviceScaleFactor(2)
                    ->waitUntilNetworkIdle()
                    ->select('#table-container');

                if ($node = env('BROWSERSHOT_NODE_PATH')) {
                    $bs->setNodeBinary($node);
                }
                if ($npm = env('BROWSERSHOT_NPM_PATH')) {
                    $bs->setNpmBinary($npm);
                }
                $exec = env('PUPPETEER_EXECUTABLE_PATH') ?: env('BROWSERSHOT_CHROME_PATH');
                if ($exec) {
                    if (method_exists($bs, 'setChromePath')) {
                        $bs->setChromePath($exec);
                    } elseif (method_exists($bs, 'setChromiumPath')) {
                        $bs->setChromiumPath($exec);
                    }
                }
                if (env('BROWSERSHOT_NO_SANDBOX', true)) {
                    $bs->setOption('args', ['--no-sandbox','--disable-setuid-sandbox','--disable-dev-shm-usage']);
                }

                $bs->save($absolutePath);
                $facture->facture_image = $relativePath;
                $facture->save();
                if ($oldAbsolute && File::exists($oldAbsolute)) {
                    @unlink($oldAbsolute);
                }
                $done = true;
            } catch (\Throwable $e) {
                logger()->error('Browsershot failed (regenerate-show)', ['error' => $e->getMessage(), 'facture_id' => $facture->id]);
            }
        }

        if ($done) {
            notify()->success('Image de la facture regénérée');
        } else {
            notify()->error("Echec de regénération de l'image de la facture");
        }

        // Show the facturePay page
        $data = company::all();
        $plan = $facture->plan;
        $factureFlag = 1; // to match existing view logic
        $vendeur = $facture->number_pos;
        $facture_id = $facture->id;
        return view('superadmin.facturePay', compact('data','plan','factureFlag','compagnie','vendeur','date','facture_id'));
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
