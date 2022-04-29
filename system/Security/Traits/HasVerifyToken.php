<?php

namespace System\Security\Traits;

use Firebase\JWT\JWT;
use System\Session\Session;

trait HasVerifyToken
{
    public static function verifyToken($token)
    {
        return self::getToken() === $token;
    }

    public static function verifyCryptToken($token)
    {
        return self::getCryptToken() === $token;
    }

    public static function verifyIpToken($token)
    {
        return self::getIpToken() === $token;
    }

    public static function verifyStartToken($token){
        return Session::get("_token") === $token;
    }

    public static function verifyStartCryptToken($token){
        return Session::get("_crypt_token") === $token;
    }

    public static function verifyStartIpToken($token){
        return Session::get("_ip_token") === $token;
    }

    public static function verifyStartRandomToken($token){
        return Session::get("_random_token") === $token;
    }

    public static function verifyStartRandomIpToken($token){
        return Session::get("_random_ip_token") === $token;
    }
}
