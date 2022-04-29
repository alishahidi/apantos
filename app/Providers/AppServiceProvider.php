<?php

namespace App\Providers;

use App\Ads;
use App\News;
use App\User;
use System\View\Composer;

class AppServiceProvider extends Provider
{
    public function boot()
    {
        return Composer::view("app.index", function () {
            // 
        });
    }
}
