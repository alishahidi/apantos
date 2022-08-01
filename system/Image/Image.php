<?php

namespace System\Image;

use System\Image\Traits\HasBuilder;

class Image
{
    use HasBuilder;

    private static $instance;

    private $allMethods = ['make', 'resize', 'fit', 'watermark', 'text', 'save', 'encode'];

    private $allowedMethods = ['make'];

    private function __construct()
    {
    }

    private static function getInstance()
    {
        self::$instance = new self();

        return self::$instance;
    }

    public function __call($method, $argvs)
    {
        return $this->methodCaller($this, $method, $argvs);
    }

    public static function __callStatic($method, $argvs)
    {
        $instance = self::getInstance();

        return $instance->methodCaller($instance, $method, $argvs);
    }

    private function methodCaller($object, $method, $argvs)
    {
        $suffix = 'Method';
        $methodName = "$method{$suffix}";
        if (in_array($method, $this->allowedMethods)) {
            return call_user_func_array([$object, $methodName], $argvs);
        }
    }

    protected function setAllowedMethods($array)
    {
        $this->allowedMethods = $array;
    }
}
