<?php

namespace System\Security\Traits;

trait HasPassword
{
    public static function getPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function cheackPassword($protectedKey, $password)
    {
        return password_verify($password, $protectedKey);
    }

    public static function verifyPassword($password)
    {
        return (bool) password_get_info($password)['algo'];
    }
}
