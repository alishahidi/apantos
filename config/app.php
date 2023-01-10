<?php

return [
    'APP_TITLE' => 'apantos project',
    'TOKEN' => 'env',
    'CRYPT_TOKEN' => 'env',
    'BASE_URL' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' . $_SERVER['HTTP_HOST'] : 'http://' . $_SERVER['HTTP_HOST'],
    'BASE_DIR' => dirname(__DIR__),
    'ERRORS' => [
        '400' => 'errors.400',
        '401' => 'errors.401',
        '404' => 'errors.404',
    ],
    // providers
    'PROVIDERS' => [
        \App\Providers\AppServiceProvider::class,
    ],
    'UN_VERIFY_TOKEN_ROUTE' => [],
];
