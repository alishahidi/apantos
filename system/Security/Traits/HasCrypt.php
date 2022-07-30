<?php

namespace System\Security\Traits;

use Defuse\Crypto\Crypto;

trait HasCrypt
{
    public static function encrypt($data)
    {
        return Crypto::encrypt($data, self::getSafeToken(self::getToken()));
    }

    public static function decrypt($ciphertext)
    {
        try {
            return Crypto::decrypt($ciphertext, self::getSafeToken(self::getToken()));
        } catch (\Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException $ex) {
            return false;
        }
    }
}
