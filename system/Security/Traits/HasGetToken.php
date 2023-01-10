<?php

namespace System\Security\Traits;

use Defuse\Crypto\Key;
use Firebase\JWT\JWT;
use System\Config\Config;

trait HasGetToken
{
    public static function getSafeToken($token)
    {
        return Key::loadFromAsciiSafeString(JWT::urlsafeB64Decode($token));
    }

    public static function getToken()
    {
        return Config::get('TOKEN');
    }

    public static function getIpToken()
    {
        return JWT::urlsafeB64Encode(hash('sha512', Config::get('TOKEN') . self::ip()));
    }
}
