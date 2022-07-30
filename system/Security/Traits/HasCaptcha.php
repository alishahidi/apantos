<?php

namespace System\Security\Traits;

use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;

trait HasCaptcha
{
    public static function buildCaptcha()
    {
        $captcha = new CaptchaBuilder();
        $_SESSION['_captcha'] = $captcha->getPhrase();
        header('Content-Type: image/jpeg');
        $captcha
            ->build()
            ->output();
        exit;
    }

    public static function buildInnerCaptcha()
    {
        $captcha = new CaptchaBuilder();
        $_SESSION['_captcha'] = $captcha->getPhrase();
        $captcha->build();

        return $captcha->inline();
    }

    public static function verifyCaptcha($userCaptchaCode)
    {
        return PhraseBuilder::comparePhrases($_SESSION['_captcha'], $userCaptchaCode);
    }
}
