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

    public function methodField()
    {
        $method_field = strtolower($_SERVER['REQUEST_METHOD']);

        if ($method_field == 'post' && isset($_POST['_method'])) {
            $methods = ['put', 'delete'];

            $method_field = (in_array($_POST['_method'], $methods))
            ? $_POST['_method']
            : $method_field;
        }

        return $method_field;
    }

    private function matchMethod()
    {
        $reservedRoutes = $this->routes[$this->method_field];

        return (new Find($reservedRoutes, $this->current_route))->handle();
    }

    public function run()
    {
        $this->match = $this->matchMethod();

        (new Controller($this->match))->handle();
    }
}
