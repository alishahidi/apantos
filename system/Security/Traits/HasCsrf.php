<?php

namespace System\Security\Traits;

use System\Session\Session;

trait HasCsrf
{
    public static function setCsrf()
    {
        $token = self::generateUserAgentToken();
        Session::set('_csrf', ['token' => $token]);

        return true;
    }

    public static function getCsrf()
    {
        return Session::get('_csrf')->token;
    }

    public static function veirfyCsrf($token)
    {
        return self::getCsrf() === $token;
    }
}
