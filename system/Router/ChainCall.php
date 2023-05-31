<?php

namespace System\Router;

use Exception;
use ReflectionClass;

class ChainCall
{
    private static $class;

    private static $params;

    public function __call($method, $args)
    {
        if(empty(self::class)) return (new $this);

        return $this->handle($method, $args);
    }

    public static function __callStatic($method, $args)
    {
        if(! method_exists(self::class, $method))
            return throw new Exception("Cannot find method {$method}");

        (new self)->$method(...$args);

        return new self;
    }

    private function instanse($class, $params = null)
    {
        self::$class = $class;

        if(!empty($params));
            self::$params = $params;
    }

    private function handle($method, $args)
    {
        $result = $this->call($method, $args);

        if($result === null)
            return (new self);

        if(is_object($result))
            return (new $result);

        return $result;
    }

    private function call($method, $args)
    {
        $params = self::$params;

        if($params === null)
            return ((new self::$class)->$method(...$args));

        $parameters = (new ReflectionClass(self::$class))->getConstructor()->getParameters();

        if(count($params) == count($parameters))
            return ((new self::$class(...$params))->$method(...$args));

        return ((new self::$class($params))->$method(...$args));
    }
}