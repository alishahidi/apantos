<?php

namespace System\Security\Traits;

trait HasVerifyToken
{
    public static function verifyToken($token)
    {
        return self::getToken() === $token;
    }

    public static function verifyIpToken($token)
    {
        return self::getIpToken() === $token;
    }
}
