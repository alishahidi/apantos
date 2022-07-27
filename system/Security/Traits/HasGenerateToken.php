<?php

namespace System\Security\Traits;

use Defuse\Crypto\Key;
use Firebase\JWT\JWT;

trait HasGenerateToken
{
    public static function generateToken()
    {
        return JWT::urlsafeB64Encode(Key::createNewRandomKey()->saveToAsciiSafeString());
    }

    public static function generateIpToken()
    {
        return JWT::urlsafeB64Encode(hash('sha512', bin2hex(openssl_random_pseudo_bytes(32)).self::ip()));
    }
}
