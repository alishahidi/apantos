<?php

namespace System\Router\Web;

class Route{

    public static function get($url, $executeMethod, $name = null){
        $executeMethod = explode('@', $executeMethod);
        $class = $executeMethod[0];
        $method = $executeMethod[1];
        global $routes;
        array_push($routes['get'], array('url' => trim($url, "/ "), 'class' => $class, 'method' => $method, 'name' => $name));
    }

    public static function post($url, $executeMethod, $name = null){

        $executeMethod = explode('@', $executeMethod);
        $class = $executeMethod[0];
        $method = $executeMethod[1];
        global $routes;
        array_push($routes['post'], array('url' => trim($url, "/ "), 'class' => $class, 'method' => $method, 'name' => $name));
    }

    public static function put($url, $executeMethod, $name = null){

        $executeMethod = explode('@', $executeMethod);
        $class = $executeMethod[0];
        $method = $executeMethod[1];
        global $routes;
        array_push($routes['put'], array('url' => trim($url, "/ "), 'class' => $class, 'method' => $method, 'name' => $name));
    }

    public static function delete($url, $executeMethod, $name = null){

        $executeMethod = explode('@', $executeMethod);
        $class = $executeMethod[0];
        $method = $executeMethod[1];
        global $routes;
        array_push($routes['delete'], array('url' => trim($url, "/ "), 'class' => $class, 'method' => $method, 'name' => $name));
    }
}