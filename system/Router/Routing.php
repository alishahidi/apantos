<?php

namespace System\Router;

use stdClass;
use System\Config\Config;

class Routing extends stdClass
{
    private $current_route;

    private $method_field;

    private $routes;

    private $match;

    public function __construct()
    {
        global $routes;

        $this->current_route = explode('/', trim(Config::get('app.CURRENT_ROUTE'), '/'));
        $this->method_field = $this->methodField();
        $this->routes = $routes;
    }

    private function requestMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function methodField()
    {
        if ($postMethod = $this->postMethod())
                return $postMethod;

        return $this->requestMethod();
    }

    private function existPostMethod()
    {
        return ($this->requestMethod() == 'post' && isset($_POST['_method']));
    }

    private function postMethod()
    {
        if(! $this->existPostMethod()) return;

        $methods = ['put', 'delete'];

        if(in_array($_POST['_method'], $methods)) return $_POST['_method'];
    }

    private function matchMethod()
    {
        $reservedRoutes = $this->routes[$this->method_field];

        return ChainCall::instanse(Find::class, [$reservedRoutes, $this->current_route])
        ->handle();
    }

    public function run()
    {
        $this->match = $this->matchMethod();

        ChainCall::instanse(Controller::class, [$this->match])
            ->handle();
    }
}
