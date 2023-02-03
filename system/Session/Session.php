<?php

namespace System\Session;

use stdClass;
use System\Security\Security;

class Session extends stdClass
{
    public static function set($name, $valueArray)
    {
        $_SESSION[$name] = Security::encrypt(Security::jwtEncode($valueArray));
    }

    public static function get($name)
    {
        if (! isset($_SESSION[$name])) {
            return false;
        }
        $token = $_SESSION[$name];
        $payload = Security::jwtDecode(Security::decrypt($token));
        if ($payload) {
            return $payload;
        }
        self::remove($name);

        return false;
    }

    public static function remove($name)
    {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
    }
}
