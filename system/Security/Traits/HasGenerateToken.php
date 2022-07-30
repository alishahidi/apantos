<?php

namespace System\Security\Traits;

use Defuse\Crypto\Key;
use Firebase\JWT\JWT;
use System\Config\Config;

trait HasGenerateToken
{
    public static function generateToken()
    {
        return JWT::urlsafeB64Encode(Key::createNewRandomKey()->saveToAsciiSafeString().Config::get('TOKEN'));
    }

    public static function generateIpToken()
    {
        return JWT::urlsafeB64Encode(hash('sha512', bin2hex(openssl_random_pseudo_bytes(32)).self::ip().Config::get('TOKEN')));
    }

    public static function generateIpUserAgentToken()
    {
        return JWT::urlsafeB64Encode(hash('sha512', bin2hex(openssl_random_pseudo_bytes(32)).self::ip().self::userAgent().Config::get('TOKEN')));
    }
}
