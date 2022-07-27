<?php

namespace App\Providers;

use System\View\Composer;

class AppServiceProvider extends Provider
{
    public function boot()
    {
        return Composer::view('app.index', function () {
            //
        });
    }
}
