<?php
namespace System\Router;

use Exception;
use System\Config\Config;

class ErrorHandler
{
    public function handle($code)
    {
        if(! ($view = Config::get('app.ERRORS'.'.'.$code)))
            return throw new Exception("Cannot find page {$code}");

        http_response_code($code);
        header($_SERVER['SERVER_PROTOCOL']."{$code}");

        return view($view);
    }
}