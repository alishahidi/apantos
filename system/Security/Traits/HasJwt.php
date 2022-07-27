<?php

namespace System\Security\Traits;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use System\Config\Config;

trait HasJwt
{
    public static function jwtExpEncode($data, $expTime)
    {
        $currentTime = time();
        $payload = [
            'iss' => Config::get('app.APP_TITLE'),
            'iat' => $currentTime,
            'exp' => $currentTime + $expTime,
        ];
        $payload = array_merge($payload, $data);

        return JWT::urlsafeB64Encode(JWT::encode($payload, self::getCryptToken(), 'HS256'));
    }

    public static function jwtEncode($data)
    {
        $payload = [
            'iss' => Config::get('app.APP_TITLE'),
        ];
        $payload = array_merge($payload, $data);

        return JWT::urlsafeB64Encode(JWT::encode($payload, self::getCryptToken(), 'HS256'));
    }

    public static function jwtDecode($token)
    {
        try {
            return JWT::decode(JWT::urlsafeB64Decode($token), new Key(self::getCryptToken(), 'HS256'));
        } catch (\Exception $e) {
            return false;
        }
    }
}
