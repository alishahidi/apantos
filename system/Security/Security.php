<?php

namespace System\Security;

use System\Security\Traits\HasCaptcha;
use System\Security\Traits\HasCrypt;
use System\Security\Traits\HasGenerateToken;
use System\Security\Traits\HasGetToken;
use System\Security\Traits\HasIp;
use System\Security\Traits\HasJwt;
use System\Security\Traits\HasPassword;
use System\Security\Traits\HasStartToken;
use System\Security\Traits\HasVerifyToken;

class Security
{
    use HasGenerateToken, HasGetToken, HasIp, HasStartToken, HasVerifyToken, HasPassword, HasCrypt, HasJwt, HasCaptcha;

    const JWT_ALG = "HS256";
    const JWT_TYP = "jwt";
    
    private static $instance;

    private function __construct()
    {
    }

    private static function getInstance()
    {
        if (empty(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

    public static function __callStatic($name, $arguments)
    {
        $instance = self::getInstance();
        return call_user_func_array([$instance, $name], $arguments);
    }
}
