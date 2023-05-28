<?php
namespace System\Router;
use System\Config\Config;

class ErrorHandler
{
    private $code;

    private $configPathErrors = 'app.ERRORS';

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function handle()
    {
        $code = $this->code;

        if(! array_key_exists($code, Config::get($this->configPathErrors))) return;

        http_response_code($code);

        header($_SERVER['SERVER_PROTOCOL']."{$code}");

        $this->view();
    }

    //TODO check exists file path
    private function view()
    {
        view(Config::get($this->configPathErrors.'.'.$this->code));

        exit;
    }
}