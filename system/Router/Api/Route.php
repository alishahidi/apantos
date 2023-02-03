<?php

namespace System\Router\Api;

use stdClass;

class Route extends stdClass
{
    public static function get($url, $executeMethod, $name = null)
    {
        $executeMethod = explode('@', $executeMethod);
        $class = $executeMethod[0];
        $method = $executeMethod[1];
        global $routes;
        array_push($routes['get'], ['url' => 'api/'.trim($url, '/ '), 'class' => $class, 'method' => $method, 'name' => $name]);
    }

    public static function post($url, $executeMethod, $name = null)
    {
        $executeMethod = explode('@', $executeMethod);
        $class = $executeMethod[0];
        $method = $executeMethod[1];
        global $routes;
        array_push($routes['post'], ['url' => 'api/'.trim($url, '/ '), 'class' => $class, 'method' => $method, 'name' => $name]);
    }
}
