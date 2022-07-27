<?php

namespace System\Security\Traits;

use Defuse\Crypto\KeyProtectedByPassword;
use Exception;
use Firebase\JWT\JWT;

trait HasPassword
{
    public static function getPassword($password)
    {
        $protected_key = KeyProtectedByPassword::createRandomPasswordProtectedKey($password);

        return JWT::urlsafeB64Encode($protected_key->saveToAsciiSafeString());
    }

    public static function cheackPassword($protectedKey, $password)
    {
        try {
            $protected_key = KeyProtectedByPassword::loadFromAsciiSafeString(JWT::urlsafeB64Decode($protectedKey));
            $user_key = $protected_key->unlockKey($password);

            return $user_key->saveToAsciiSafeString();
        } catch (\Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException $ex) {
            return false;
        }
    }

    public static function verifyPassword($protectedKey)
    {
        try {
            KeyProtectedByPassword::loadFromAsciiSafeString(JWT::urlsafeB64Decode($protectedKey));

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
