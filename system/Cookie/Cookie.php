<?php

namespace System\Cookie;

use System\Security\Security;

class Cookie
{
    public static function set($name, $valueArray, $time, $isSubDomain = false)
    {
        if ($isSubDomain) {
            setcookie($name, Security::encrypt(Security::jwtEncode($valueArray)), time() + $time, '/', '.'.str_replace('www.', '', currentDomain()));
        } else {
            setcookie($name, Security::encrypt(Security::jwtEncode($valueArray)), time() + $time);
        }

        return true;
    }

    public static function get($name)
    {
        if (! isset($_COOKIE[$name])) {
            return false;
        }
        $token = $_COOKIE[$name];
        $payload = Security::jwtDecode(Security::decrypt($token));
        if ($payload) {
            return $payload;
        }
        self::remove($name);

        return false;
    }

    public static function remove($name)
    {
        if (isset($_COOKIE[$name])) {
            setcookie($name, 'unset', time() - 3600);
        }
    }

    public static function __callStatic($name, $arguments)
    {
        $instance = new self();

        return call_user_func_array([$instance,  $name], $arguments);
    }
}
