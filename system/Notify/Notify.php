<?php

namespace System\Notify;

use stdClass;
use System\Notify\Traits\HasMail;

class Notify extends stdClass
{
    use HasMail;

    private static $instance;

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
