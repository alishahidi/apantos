<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $message = 'message from controller';

        return view('app.index', compact('message'));
    }
}
