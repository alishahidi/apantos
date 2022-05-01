<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use System\Auth\Auth;

class LogoutController extends Controller
{
    public function logout()
    {
        Auth::logout();
        return back();
    }
}
