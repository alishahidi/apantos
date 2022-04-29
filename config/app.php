<?php

return [
    "APP_TITLE" => "test",
    "TOKEN" => "env",
    "CRYPT_TOKEN" => "env",
    "BASE_URL" => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on") ? "https://" . $_SERVER['HTTP_HOST'] : "http://" . $_SERVER['HTTP_HOST'],
    "BASE_DIR" => dirname(__DIR__),
    // providers
    "PROVIDERS" => [
        \App\Providers\AppServiceProvider::class,
        \App\Providers\SessionServiceProvider::class,
    ],
    "UN_VERIFY_TOKEN_ROUTE" => []
];
