<?php

namespace System\Security\Traits;

use Defuse\Crypto\Key;
use Firebase\JWT\JWT;
use System\Config\Config;
use System\Session\Session;

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

    public static function getCryptToken()
    {
        return Config::get('CRYPT_TOKEN');
    }

    public static function getDirToken()
    {
        return Config::get('DIR_TOKEN');
    }

    public static function getRandomToken()
    {
        return self::generateToken();
    }

    public static function getIpToken()
    {
        return JWT::urlsafeB64Encode(hash('sha512', Config::get('TOKEN').self::ip()));
    }

    public static function getCryptIpToken()
    {
        return JWT::urlsafeB64Encode(hash('sha512', Config::get('CRYPT_TOKEN').self::ip()));
    }

    public static function getRandomIpToken()
    {
        return JWT::urlsafeB64Encode(hash('sha512', self::generateToken()));
    }

    public static function getStartToken()
    {
        return Session::get('_token');
    }

    public static function getStartCryptToken()
    {
        return Session::get('_crypt_token');
    }

    public static function getStartIpToken()
    {
        return Session::get('_ip_token');
    }

    public static function getStartRandomToken()
    {
        return Session::get('_random_token');
    }

    public static function getStartRandomIpToken()
    {
        return Session::get('_random_ip_token');
    }
}
