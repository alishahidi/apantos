<?php

namespace System\Security\Traits;

use Firebase\JWT\JWT;
use System\Session\Session;

trait HasStartToken
{
    public static function startToken(){
        Session::set("_token", self::getToken());
    }

    public static function startCryptToken(){
        Session::set("_crypt_token", self::getCryptToken());
    }

    public static function startIpToken(){
        Session::set("_ip_token", self::getIpToken());
    }

    public static function startRandomToken(){
        Session::set("_random_token", self::generateToken());
    }

    public static function startRandomIpToken(){
        Session::set("_random_ip_token", self::getRandomIpToken());
    }
}

