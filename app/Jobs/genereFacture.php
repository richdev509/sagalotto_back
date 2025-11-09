<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class genereFacture implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // create invoices for companies that expire today
        $date = Carbon::now();
        $datee = $date->copy();
        $dateString = $date->format('Y-m-d');

    $companies = DB::table('companies')->whereDate('dateexpiration', $dateString)->get();

        foreach ($companies as $company) {
            // Base plan
            $plan = $company->plan;
          
            // Skip if a facture already exists this month/year for this company (ignore day)
            $existsThisMonth = DB::table('factures')
                ->where('compagnie_id', $company->id)
                ->whereYear('due_date', $date->year)
                ->whereMonth('due_date', $date->month)
                ->exists();
            if ($existsThisMonth) {
                continue;
            }

            // Define the date range for the query
            $startDate = $datee->copy()->subDays(6)->startOfDay(); // Start of the day, 6 days before
            $endDate = $datee->copy()->subDays(1)->endOfDay(); // End of the day, 1 day before
            $vendeur = DB::table('ticket_code')
                ->where('compagnie_id', $company->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->distinct()
                ->count('user_id');
            if ($company->plan == 10) {
                if ($vendeur >= 1 && $vendeur < 10) {
                    $plan = 10;
                } elseif ($vendeur >= 10 && $vendeur < 20) {
                    $plan = 9;
                } elseif ($vendeur >= 20 && $vendeur < 30) {
                    $plan = 8;
                } elseif ($vendeur >= 30 && $vendeur < 50) {
                    $plan = 7;
                } elseif ($vendeur >= 50 && $vendeur < 10000) {
                    $plan = 6;
                }
            }
            // Prepare facture data
            $factureData = [
                'compagnie_id' => $company->id,
                'amount' => $plan * $vendeur,
                'plan' => $plan,
                'number_pos' => $vendeur,
                'paid_amount' => 0,
                'due_date' => $datee,
                'is_paid' => 0,
                'month_added' => 0,
                'paid_at' => null,
                'payment_method' => null,
                'payment_id' => null,
                'currency' => null,
                'description' => 'Facture du ' . $dateString,
                'facture_image' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Render the existing Blade view 'superadmin/facturePay' to HTML
            // The view expects: $compagnie, $vendeur, $date
            $html = View::make('superadmin.facturePay', [
                'compagnie' => $company,
                'vendeur'   => $vendeur,
                'date'      => $dateString,
            ])->render();

            // Ensure the output directory exists under public
            File::ensureDirectoryExists(public_path('factures'));

            // Build a strong, unique filename under public/factures
            $slug = Str::slug($company->name ?? 'company', '-');
            $datePart = $date->format('Ymd');
            $random = Str::random(12);
            $relativePath = "factures/{$slug}-{$company->id}-{$datePart}-{$random}.png";
            $absolutePath = public_path($relativePath);

            try {
                // Use Browsershot (headless Chrome via Puppeteer) to capture the table element as an image
                $bs = Browsershot::html($html)
                    ->windowSize(900, 1200)
                    ->deviceScaleFactor(2)
                    ->waitUntilNetworkIdle()
                    ->select('#table-container');

                // Optional runtime configuration from env for production
                if ($node = env('BROWSERSHOT_NODE_PATH')) {
                    $bs->setNodeBinary($node);
                }
                if ($npm = env('BROWSERSHOT_NPM_PATH')) {
                    $bs->setNpmBinary($npm);
                }
                $exec = env('PUPPETEER_EXECUTABLE_PATH') ?: env('BROWSERSHOT_CHROME_PATH');
                if ($exec) {
                    // prefer setChromePath when available
                    if (method_exists($bs, 'setChromePath')) {
                        $bs->setChromePath($exec);
                    } elseif (method_exists($bs, 'setChromiumPath')) {
                        $bs->setChromiumPath($exec);
                    }
                }
                if (env('BROWSERSHOT_NO_SANDBOX', true)) {
                    // Force exact args list to avoid duplicated or malformed dashes
                    $bs->setOption('args', ['--no-sandbox','--disable-setuid-sandbox','--disable-dev-shm-usage']);
                }

                $bs->save($absolutePath);

                // Store the publicly accessible relative path under public/
                $factureData['facture_image'] = $relativePath;
            } catch (\Throwable $e) {
                // Fallback: try wkhtmltoimage if available
                logger()->error('Browsershot capture failed in job', ['error' => $e->getMessage()]);
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
                        logger()->info('wkhtmltoimage output (job)', ['rc'=>$rc,'out'=>implode("\n", $out)]);
                        @unlink($tmpHtml);
                        if ($rc === 0 && file_exists($absolutePath)) {
                            $factureData['facture_image'] = $relativePath;
                        } else {
                            $factureData['facture_image'] = '';
                        }
                    } catch (\Throwable $e2) {
                        logger()->error('wkhtmltoimage fallback failed (job)', ['error' => $e2->getMessage()]);
                        $factureData['facture_image'] = '';
                    }
                } else {
                    $factureData['facture_image'] = '';
                }
            }

            DB::table('factures')->insert($factureData);
        }
    }
}
