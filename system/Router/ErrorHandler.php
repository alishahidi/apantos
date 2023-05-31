<?php
namespace System\Router;
use Exception;
use System\Config\Config;

class ErrorHandler
{
    private $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function handle()
    {
        if(! ($view = Config::get('app.ERRORS'.'.'.$code = $this->code)))
            return throw new Exception("Cannot find page {$code}");

        http_response_code($code);
        header($_SERVER['SERVER_PROTOCOL']."{$code}");

        return view($view);
    }
}