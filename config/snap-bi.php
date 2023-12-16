<?php

return [
    // environment
    'environment' => env('ASPI_APP_ENV', 'aspi'),

    'logging' => [
        'channel' => env('ASPI_LOG_CHANNEL', 'daily'),
        'log_level' => env('ASPI_LOG_LEVEL', 'debug'),
    ],

    // providers
    'providers' => [
        // test: https://apidevportal.aspi-indonesia.or.id/request-aplikasi-pengujian
        'aspi' => [
            'app_name' => env('ASPI_APP_NAME'),
            'client_id' => env('ASPI_CLIENT_ID'),
            'client_secret' => env('ASPI_CLIENT_SECRET'),
            'public_key' => env('ASPI_PUBLIC_KEY'),
            'private_key' => env('ASPI_PRIVATE_KEY'),
            'base_url' => env('ASPI_BASE_URI', 'https://apidevportal.aspi-indonesia.or.id:44310'),
        ],

        // ...others e.g: BCA, BNI, Mandiri, etc
    ],
];
