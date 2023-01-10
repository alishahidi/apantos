<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use System\Security\Security;

class TokenController extends Controller
{
    public function get()
    {
        echo Security::generateToken();
    }
}
