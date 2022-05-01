<?php

namespace App\Http\Controllers\Home;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\Post;

class HomeController extends Controller
{

    public function index()
    {
        $message = "message from controller";
        return view("app.index", compact("message"));
    }
}
