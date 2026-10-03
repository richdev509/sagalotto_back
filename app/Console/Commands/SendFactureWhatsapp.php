<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\genereFacture;

class SendFactureWhatsapp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:send-facture {company_id : The company ID to send facture to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp facture message to a specific company';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $companyId = $this->argument('company_id');

        // Get company details
        $company = DB::table('companies')->where('id', $companyId)->first();

        if (!$company) {
            $this->error("Company with ID {$companyId} not found.");
            return 1;
        }

        $this->info("Company: {$company->name}");
        $this->info("WhatsApp: {$company->whatsapp}");
        $this->info("Expiration: {$company->dateexpiration}");
        $this->line('');

        // Ask for confirmation
        if (!$this->confirm('Send WhatsApp message to this company?', true)) {
            $this->info('Cancelled.');
            return 0;
        }

        try {
            $result = genereFacture::sendFactureForCompany($companyId);

            if ($result['success']) {
                $this->info('✅ ' . $result['message']);
                return 0;
            } else {
                $this->error('❌ ' . $result['message']);
                return 1;
            }
        } catch (\Throwable $e) {
            $this->error('Exception: ' . $e->getMessage());
            return 1;
        }
    }
}
