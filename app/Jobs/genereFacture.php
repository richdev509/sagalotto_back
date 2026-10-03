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

    protected $companyId;

    /**
     * Create a new job instance.
     */
    public function __construct($companyId = null)
    {
        $this->companyId = $companyId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // create invoices for companies whose expiration day matches today's day
        $today = Carbon::today();
        $todayDay = $today->day;
        $dateString = $today->format('Y-m-d');

        if ($this->companyId) {
            $companies = DB::table('companies')
                ->where('id', $this->companyId)
                ->where('is_delete', 0)
                ->get();
        } else {
            $companies = DB::table('companies')
                ->whereRaw('DAY(dateexpiration) = ?', [$todayDay])
                ->whereDate('dateexpiration', '<=', $today)
                ->where('is_block', 0)
                ->where('is_delete', 0)
                ->get();
        }

        foreach ($companies as $company) {
            $dueDate = Carbon::parse($company->dateexpiration);
            $dueDateString = $dueDate->format('Y-m-d');

            // Check if a facture already exists for this specific expiration date
            $factureExists = DB::table('factures')
                ->where('compagnie_id', $company->id)
                ->whereDate('due_date', $dueDateString)
                ->exists();

            $plan = $company->plan;

            // Define the date range for the query (6 days before the expiration date)
            $startDate = $dueDate->copy()->subDays(6)->startOfDay();
            $endDate = $dueDate->copy()->subDays(1)->endOfDay();
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
                'due_date' => $dueDate,
                'is_paid' => 0,
                'month_added' => 0,
                'paid_at' => null,
                'payment_method' => null,
                'payment_id' => null,
                'currency' => null,
                'description' => 'Facture du ' . $dueDateString,
                'facture_image' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Only create facture if it doesn't exist
            if (!$factureExists) {
                // Render the existing Blade view 'superadmin/facturePay' to HTML
                // The view expects: $compagnie, $vendeur, $date
                $html = View::make('superadmin.facturePay', [
                    'compagnie' => $company,
                    'vendeur'   => $vendeur,
                    'date'      => $dueDateString,
                ])->render();

                // Ensure the output directory exists under public
                File::ensureDirectoryExists(public_path('factures'));

                // Build a strong, unique filename under public/factures
                $slug = Str::slug($company->name ?? 'company', '-');
                $datePart = $dueDate->format('Ymd');
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
            } else {
                logger()->info('genereFacture: facture already exists, skipping creation', [
                    'company_id' => $company->id,
                    'due_date'   => $dueDateString,
                ]);
            }

            // Send WhatsApp notification to the company (always, even if facture exists)
            try {
                self::sendWhatsapp($company, $vendeur, $plan, $factureData['amount'], $dueDateString);
                
                // Wait 60 seconds before processing next company to avoid spam detection
                $delaySeconds = (int) env('WHATSAPP_SEND_DELAY_SECONDS', 60);
                if ($delaySeconds > 0) {
                    logger()->info('genereFacture: waiting {delay}s before next send', ['delay' => $delaySeconds]);
                    sleep($delaySeconds);
                }
            } catch (\Throwable $e) {
                logger()->error('genereFacture: WhatsApp send failed', [
                    'company_id' => $company->id,
                    'error'      => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Query the Green API instance state.
     */
    public static function getGreenApiState(): string
    {
        $baseUrl    = rtrim(env('GREENAPI_BASE_URL') ?: 'https://7107.api.greenapi.com', '/');
        $instanceId = env('GREENAPI_INSTANCE') ?: '710722681718';
        $token      = env('GREENAPI_TOKEN') ?: '9c7c61edfa7040d485b998ea675cdf548c4bf66861b04e199b';

        if (!$instanceId || !$token) {
            throw new \RuntimeException('Les identifiants Green API ne sont pas configurés.');
        }

        $client = new \GuzzleHttp\Client([
            'timeout'     => 15,
            'http_errors' => false,
            'verify'      => filter_var(env('GREENAPI_SSL_VERIFY', true), FILTER_VALIDATE_BOOLEAN),
        ]);

        $response = $client->request('GET', "{$baseUrl}/waInstance{$instanceId}/getStateInstance/{$token}", [
            'headers' => ['accept' => 'application/json'],
        ]);

        $status = $response->getStatusCode();
        $body = (string) $response->getBody();

        if ($status < 200 || $status >= 300) {
            throw new \RuntimeException("Green API state check failed (HTTP {$status}): {$body}");
        }

        $payload = json_decode($body, true);
        if (!is_array($payload) || !isset($payload['stateInstance'])) {
            throw new \RuntimeException('Green API state response unexpected: ' . $body);
        }

        return $payload['stateInstance'];
    }

    /**
     * Calculate facture data for a company and send WhatsApp message.
     * This is lightweight: it does not generate the facture image,
     * so it is safe to call from a web request without timeout.
     * Returns ['success' => bool, 'message' => string].
     */
    public static function sendFactureForCompany($companyId): array
    {
        $company = DB::table('companies')->where('id', $companyId)->where('is_delete', 0)->first();
        if (!$company) {
            logger()->warning('sendFactureForCompany: company not found', ['company_id' => $companyId]);
            return ['success' => false, 'message' => 'Compagnie introuvable.'];
        }

        try {
            $state = self::getGreenApiState();
            if ($state !== 'authorized') {
                return [
                    'success' => false,
                    'message' => "L'instance Green API est en état '{$state}'. Redémarrez l'instance depuis la console Green API.",
                ];
            }
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

        $dueDate = Carbon::parse($company->dateexpiration);
        $dueDateString = $dueDate->format('Y-m-d');

        // Count active POS sellers in the 6 days before expiration
        $startDate = $dueDate->copy()->subDays(6)->startOfDay();
        $endDate = $dueDate->copy()->subDays(1)->endOfDay();
        $vendeur = DB::table('ticket_code')
            ->where('compagnie_id', $company->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->distinct()
            ->count('user_id');

        $plan = $company->plan;
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

        $amount = $plan * $vendeur;

        // Ensure a facture row exists (without image for manual web send)
        $factureExists = DB::table('factures')
            ->where('compagnie_id', $company->id)
            ->whereDate('due_date', $dueDateString)
            ->exists();

        if (!$factureExists) {
            DB::table('factures')->insert([
                'compagnie_id' => $company->id,
                'amount' => $amount,
                'plan' => $plan,
                'number_pos' => $vendeur,
                'paid_amount' => 0,
                'due_date' => $dueDate,
                'is_paid' => 0,
                'month_added' => 0,
                'paid_at' => null,
                'payment_method' => null,
                'payment_id' => null,
                'currency' => null,
                'description' => 'Facture du ' . $dueDateString,
                'facture_image' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        try {
            self::sendWhatsapp($company, (int) $vendeur, $plan, $amount, $dueDateString);
            return ['success' => true, 'message' => 'Message WhatsApp envoyé avec succès.'];
        } catch (\Throwable $e) {
            logger()->error('sendFactureForCompany: WhatsApp send failed', [
                'company_id' => $companyId,
                'error'      => $e->getMessage(),
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Send a WhatsApp message to the company with the facture details.
     * Returns true on success, false on Green API non-2xx response.
     * Throws RuntimeException for missing phone, invalid phone, missing credentials, or transport errors.
     */
    public static function sendWhatsapp($company, int $vendeur, $plan, $amount, string $dateString): bool
    {
        $phone = trim($company->whatsapp ?? '');
        if ($phone === '') {
            throw new \RuntimeException('Aucun numéro WhatsApp enregistré pour cette compagnie.');
        }

        // Normalize phone: digits only, no spaces or +
        $phone = preg_replace('/\D/', '', $phone);
        if ($phone === '') {
            throw new \RuntimeException('Le numéro WhatsApp de la compagnie est invalide.');
        }

        // Remove leading zero from local format, then prepend default country code if missing
        $phone = ltrim($phone, '0');
        $defaultCountryCode = preg_replace('/\D/', '', env('GREENAPI_DEFAULT_COUNTRY_CODE', '509'));
        if ($defaultCountryCode && strpos($phone, $defaultCountryCode) !== 0 && strlen($phone) < 10) {
            $phone = $defaultCountryCode . $phone;
        }

        if (strlen($phone) < 10) {
            throw new \RuntimeException('Le numéro WhatsApp est trop court. Utilisez le format international complet (ex: 50948698274).');
        }

        $baseUrl    = rtrim(env('GREENAPI_BASE_URL') ?: 'https://7107.api.greenapi.com', '/');
        $instanceId = env('GREENAPI_INSTANCE') ?: '710722681718';
        $token      = env('GREENAPI_TOKEN') ?: '9c7c61edfa7040d485b998ea675cdf548c4bf66861b04e199b';

        if (!$instanceId || !$token) {
            throw new \RuntimeException('Les identifiants Green API ne sont pas configurés.');
        }

        $amountUsd  = number_format((float)$amount, 2);
        $amountGdes = number_format((float)$amount * 133, 2);

        $message =
            "📋 *Facture Sagaloto - {$dateString}*\n\n" .
            "Bonjou *{$company->name}*,\n\n" .
            "Facture ou pou mwa sa a jenere otomatikman.\n\n" .
            "📌 *Detay Facture:*\n" .
            "  • Kantite POS aktif : *{$vendeur}*\n" .
            "  • Tarif pa POS      : *\${$plan}*\n" .
            "  • Total             : *\${$amountUsd} USD*\n" .
            "  • Ekivalan Gourdes  : *{$amountGdes} HTG* (1\$ = 133G)\n\n" .
            "💳 *Mwayen Peman:*\n" .
            "  • Natcash : *55175521* — Ricardo Rosalvo\n" .
            "  • Natcash : *43867772* — Barthelemy Wania\n" .
            "  • Moncash : *48698274* — Ricardo Rosalvo\n" .
            "  • Pou tout lòt mwayen peman, kontakte administrasyon an.\n\n" .
            "📸 Lèw fin peye, pataje foto peman an avèk nou svp. San foto peman an, nou pap ka idantifye pou kiyès peman an.\n\n" .
            "⚠️ Ou gen yon delè 5 jou pou peye. Apre sa sistèm nan ap bloke otomatikman.\n\n" .
            "Mèsi! 🙏";

        $client = new \GuzzleHttp\Client([
            'timeout'     => 15,
            'http_errors' => false,
            'verify'      => filter_var(env('GREENAPI_SSL_VERIFY', true), FILTER_VALIDATE_BOOLEAN),
        ]);
        $url = "{$baseUrl}/waInstance{$instanceId}/sendMessage/{$token}";

        $response = $client->request('POST', $url, [
            'headers' => [
                'accept'        => 'application/json',
                'content-type'  => 'application/json',
            ],
            'json' => [
                'chatId'  => $phone . '@c.us',
                'message' => $message,
            ],
        ]);

        $status = $response->getStatusCode();
        $body = (string) $response->getBody();

        if ($status < 200 || $status >= 300) {
            throw new \RuntimeException("Green API a répondu HTTP {$status}: {$body}");
        }

        $payload = json_decode($body, true);

        // Green API returns errors inside a 200 response too
        if (!is_array($payload) || !isset($payload['idMessage'])) {
            $error = is_array($payload) ? json_encode($payload) : $body;
            throw new \RuntimeException("Green API n'a pas accepté le message: {$error}");
        }

        logger()->info('genereFacture: WhatsApp sent via Green API', [
            'company_id' => $company->id,
            'status'     => $status,
            'body'       => $body,
        ]);

        return true;
    }
}
