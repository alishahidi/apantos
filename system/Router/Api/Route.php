<?php

namespace System\Router\Api;

class Route{
    public static function get($url, $executeMethod, $name = null){

        $executeMethod = explode('@', $executeMethod);
        $class = $executeMethod[0];
        $method = $executeMethod[1];
        global $routes;
        array_push($routes['get'], array('url' => "api/".trim($url, "/ "), 'class' => $class, 'method' => $method, 'name' => $name));
    }

    public static function post($url, $executeMethod, $name = null){

        $executeMethod = explode('@', $executeMethod);
        $class = $executeMethod[0];
        $method = $executeMethod[1];
        global $routes;
        array_push($routes['post'], array('url' => "api/".trim($url, "/ "), 'class' => $class, 'method' => $method, 'name' => $name));
    }
}

