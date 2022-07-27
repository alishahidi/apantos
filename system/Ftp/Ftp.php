<?php

namespace System\Ftp;

use System\Ftp\Traits\HasClient;

class Ftp
{
    use HasClient;

    private static $instance;

    protected static $filesystem;

    private function __construct()
    {
    }

    private static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function __callStatic($name, $arguments)
    {
        $instance = self::getInstance();

        return call_user_func_array([$instance, $name], $arguments);
    }
}
