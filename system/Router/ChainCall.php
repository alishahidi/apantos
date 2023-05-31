<?php

namespace System\Router;

class ChainCall
{
    private static $class;

    public function __call($method, $args)
    {
        if(empty(self::$class))
            return (new static)->$method(...$args);

        $result = (new self::$class)->$method(...$args);

        if(empty($result))
            return new self;

        if(is_object($result))
            return (new $result);

        return $result;
    }

    public static function __callStatic($method, $args)
    {
        if(! method_exists(self::class, $method))
            return (new static)->$method(...$args);

        (new self)->$method(...$args);

        return new self;
    }

    private function call($class)
    {
        self::$class = $class;
    }
}