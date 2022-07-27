<?php

namespace System\Router\Web;

class Route
{
    public static function get($url, $executeMethod, $name = null)
    {
        $executeMethod = explode('@', $executeMethod);
        $class = $executeMethod[0];
        $method = $executeMethod[1];
        global $routes;
        array_push($routes['get'], ['url' => trim($url, '/ '), 'class' => $class, 'method' => $method, 'name' => $name]);
    }

    public static function post($url, $executeMethod, $name = null)
    {
        $executeMethod = explode('@', $executeMethod);
        $class = $executeMethod[0];
        $method = $executeMethod[1];
        global $routes;
        array_push($routes['post'], ['url' => trim($url, '/ '), 'class' => $class, 'method' => $method, 'name' => $name]);
    }

    public static function put($url, $executeMethod, $name = null)
    {
        $executeMethod = explode('@', $executeMethod);
        $class = $executeMethod[0];
        $method = $executeMethod[1];
        global $routes;
        array_push($routes['put'], ['url' => trim($url, '/ '), 'class' => $class, 'method' => $method, 'name' => $name]);
    }

    public static function delete($url, $executeMethod, $name = null)
    {
        $executeMethod = explode('@', $executeMethod);
        $class = $executeMethod[0];
        $method = $executeMethod[1];
        global $routes;
        array_push($routes['delete'], ['url' => trim($url, '/ '), 'class' => $class, 'method' => $method, 'name' => $name]);
    }
}
