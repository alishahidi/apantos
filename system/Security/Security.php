<?php

namespace System\Security;

use stdClass;
use System\Security\Traits\HasCaptcha;
use System\Security\Traits\HasCrypt;
use System\Security\Traits\HasCsrf;
use System\Security\Traits\HasGenerateToken;
use System\Security\Traits\HasGetToken;
use System\Security\Traits\HasIp;
use System\Security\Traits\HasJwt;
use System\Security\Traits\HasPassword;
use System\Security\Traits\HasVerifyToken;

class Security extends stdClass
{
    use HasGenerateToken;
    use HasGetToken;
    use HasIp;
    use HasCsrf;
    use HasVerifyToken;
    use HasPassword;
    use HasCrypt;
    use HasJwt;
    use HasCaptcha;

    public const JWT_ALG = 'HS256';

    public const JWT_TYP = 'jwt';

    private static $instance;

    private function __construct()
    {
    }

    private static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function __callStatic($name, $arguments)
    {
        $instance = self::getInstance();

        return call_user_func_array([$instance, $name], $arguments);
    }
}
