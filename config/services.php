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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id'          => env('GOOGLE_CLIENT_ID'),
        'client_secret'      => env('GOOGLE_CLIENT_SECRET'),
        'redirect'           => env('GOOGLE_REDIRECT_URI'),
        'vision_credentials' => (function (): ?string {
            $path = env('GOOGLE_APPLICATION_CREDENTIALS');
            if ($path === null || $path === '') return null;
            $esAbsoluto = str_starts_with($path, '/') || preg_match('/^[A-Za-z]:[\\\\\/]/', $path);
            return $esAbsoluto ? $path : storage_path('app/google/'.$path);
        })(),
    ],

    'mercado_pago' => [

        'token' => env('MERCADO_PAGO_ACCESS_TOKEN'),
        'public_key' => env('MERCADO_PAGO_PUBLIC_KEY'),
    ],

    'tesseract' => [
        'binary_path' => env('TESSERACT_BINARY_PATH', 'C:\Program Files\Tesseract-OCR\tesseract.exe'),
    ],

];
