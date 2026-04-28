<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Culqi API Keys
    |--------------------------------------------------------------------------
    | Configura tus claves en el archivo .env.
    | Usa las claves de PRUEBA (pk_test_ / sk_test_) en sandbox.
    | Para producción usa las claves pk_live_ / sk_live_.
    */

    'public_key' => env('CULQI_PUBLIC_KEY', ''),
    'secret_key' => env('CULQI_SECRET_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Culqi API URL (Sandbox)
    |--------------------------------------------------------------------------
    | La URL base de la API de Culqi. No cambiar para sandbox.
    | Para producción es la misma URL (Culqi usa la key para distinguir).
    */
    'api_url' => env('CULQI_API_URL', 'https://api.culqi.com/v2'),
];
