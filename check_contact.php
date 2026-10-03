<?php
require 'vendor/autoload.php';
$client = new GuzzleHttp\Client(['verify' => false]);
try {
    $response = $client->request('POST', 'https://7107.api.greenapi.com/waInstance710722681718/getContactInfo/9c7c61edfa7040d485b998ea675cdf548c4bf66861b04e199b', [
        'headers' => ['accept' => 'application/json', 'content-type' => 'application/json'],
        'json' => ['chatId' => '50955175521@c.us']
    ]);
    echo 'HTTP ' . $response->getStatusCode() . PHP_EOL;
    echo (string) $response->getBody();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
