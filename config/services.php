<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'greenapi' => [
        'base_url' => env('GREENAPI_BASE_URL', 'https://7107.api.greenapi.com'),
        'instance' => env('GREENAPI_INSTANCE', '710722681718'),
        'token' => env('GREENAPI_TOKEN', '9c7c61edfa7040d485b998ea675cdf548c4bf66861b04e199b'),
        'ssl_verify' => env('GREENAPI_SSL_VERIFY', true),
    ],

];
