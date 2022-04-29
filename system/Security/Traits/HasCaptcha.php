<?php

namespace System\Security\Traits;

use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use System\Request\Request;
use System\Session\Session;

trait HasCaptcha
{
    public static function buildCaptcha()
    {
        $captcha = new CaptchaBuilder;
        $_SESSION["phrase"] = $captcha->getPhrase();
        header('Content-Type: image/jpeg');
        $captcha
            ->build()
            ->output();
        exit;
    }

    public static function buildInnerCaptcha()
    {
        $captcha = new CaptchaBuilder();
        $_SESSION['phrase'] = $captcha->getPhrase();
        $captcha->build();
        return $captcha->inline();
    }

    public static function verifyCaptcha($userCaptchaCode)
    {
        return PhraseBuilder::comparePhrases($_SESSION["phrase"], $userCaptchaCode);
    }
}
