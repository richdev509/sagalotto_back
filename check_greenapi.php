<?php
require 'vendor/autoload.php';
$client = new GuzzleHttp\Client(['verify' => false]);
try {
    $response = $client->request('GET', 'https://7107.api.greenapi.com/waInstance710722681718/getStateInstance/9c7c61edfa7040d485b998ea675cdf548c4bf66861b04e199b');
    echo 'HTTP ' . $response->getStatusCode() . PHP_EOL;
    $body = (string) $response->getBody();
    echo 'Body length: ' . strlen($body) . PHP_EOL;
    var_dump($body);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
