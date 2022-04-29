<?php

namespace System\Security\Traits;

use Defuse\Crypto\Crypto;

trait HasCrypt
{
    public static function encrypt($data)
    {
        return Crypto::encrypt($data, self::getSafeToken(self::getCryptToken()));
    }

    public static function decrypt($ciphertext)
    {
        try {
            return Crypto::decrypt($ciphertext, self::getSafeToken(self::getCryptToken()));
        } catch (\Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException $ex) {
            return false;
        }
    }
}
