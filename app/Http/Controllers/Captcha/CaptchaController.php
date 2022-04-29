<?php

namespace App\Http\Controllers\Captcha;

use App\Http\Controllers\Controller;
use System\Security\Security;

class CaptchaController extends Controller
{
    public function get()
    {
        return Security::buildCaptcha();
    }
}
