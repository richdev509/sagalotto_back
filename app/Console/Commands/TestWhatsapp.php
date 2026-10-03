<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class TestWhatsapp extends Command
{
    protected $signature = 'test:whatsapp {phone} {--message=Test message from Sagaloto}';
    protected $description = 'Test sending a WhatsApp message via Green API';

    public function handle()
    {
        $phone = preg_replace('/\D/', '', $this->argument('phone'));
        if ($phone === '') {
            $this->error('Numéro WhatsApp invalide.');
            return 1;
        }

        // Remove leading zero from local format, then prepend default country code if missing
        $phone = ltrim($phone, '0');
        $defaultCountryCode = preg_replace('/\D/', '', env('GREENAPI_DEFAULT_COUNTRY_CODE', '509'));
        if ($defaultCountryCode && strpos($phone, $defaultCountryCode) !== 0 && strlen($phone) < 10) {
            $phone = $defaultCountryCode . $phone;
        }

        if (strlen($phone) < 10) {
            $this->error('Le numéro WhatsApp est trop court. Utilisez le format international complet (ex: 50948698274).');
            return 1;
        }

        $message = $this->option('message');

        $baseUrl    = rtrim(env('GREENAPI_BASE_URL') ?: 'https://7107.api.greenapi.com', '/');
        $instanceId = env('GREENAPI_INSTANCE') ?: '710722681718';
        $token      = env('GREENAPI_TOKEN') ?: '9c7c61edfa7040d485b998ea675cdf548c4bf66861b04e199b';

        if (!$instanceId || !$token) {
            $this->error('Green API credentials are not configured');
            return 1;
        }

        $url = "{$baseUrl}/waInstance{$instanceId}/sendMessage/{$token}";

        $this->info("Sending to: {$phone}@c.us");
        $this->info("URL: {$url}");
        $this->info("Message: {$message}");

        try {
            $client = new Client([
                'timeout'     => 15,
                'http_errors' => false,
                'verify'      => filter_var(env('GREENAPI_SSL_VERIFY', true), FILTER_VALIDATE_BOOLEAN),
            ]);
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
                $this->error("Failed (HTTP {$status}): {$body}");
                return 1;
            }

            $payload = json_decode($body, true);
            if (!is_array($payload) || !isset($payload['idMessage'])) {
                $error = is_array($payload) ? json_encode($payload) : $body;
                $this->error("Green API did not accept the message: {$error}");
                return 1;
            }

            $this->info("Success (HTTP {$status}): {$body}");
            return 0;
        } catch (RequestException $e) {
            $this->error('Request error: ' . $e->getMessage());
            if ($e->hasResponse()) {
                $this->error('Response: ' . (string) $e->getResponse()->getBody());
            }
            return 1;
        } catch (\Throwable $e) {
            $this->error('Exception: ' . $e->getMessage());
            return 1;
        }
    }
}
