<?php

namespace System\Router;

use ReflectionMethod;
use stdClass;
use System\Config\Config;

class Routing extends stdClass
{
    private $current_route;

    private $method_field;

    private $routes;

    private $values = [];

    private $compare = false;

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

    private function find($reserve)
    {
        if ($this->compare($reserve['url']))
            return [
                'class' => $reserve['class'],
                'method' => $reserve['method']
            ];
    }

    private function matchMethod()
    {
        $reservedRoutes = $this->routes[$this->method_field];

        foreach($reservedRoutes as $reservedRoute)
        {
            if($find = $this->find($reservedRoute)) return $find;

            $this->values = [];
        }

        return [];
    }

    private function checkEmptyMatch()
    {
        if (empty($this->match)) $this->error404();
    }

    private function checkExistsClass()
    {
        $classPath = str_replace('\\', '/', $this->match['class']);
        $path = Config::get('app.BASE_DIR').'/app/Http/Controllers/'.$classPath.'.php';

        if (! file_exists($path)) $this->error404();
    }

    private function runController()
    {
        $class = "\App\Http\Controllers\\".$this->match['class'];
        $object = new $class();

        if (! method_exists($object, $this->match['method']))
            $this->error404();

        $reflection = new ReflectionMethod($class, $this->match['method']);
        $parameterCount = $reflection->getNumberOfParameters();

        if (! $parameterCount <= count($this->values))
            $this->error404();

        call_user_func_array([$object, $this->match['method']], $this->values);
    }

    public function run()
    {
        $this->match = $this->matchMethod();

        $this->checkEmptyMatch();

        $this->checkExistsClass();

        $this->runController();
    }

    private function compareRootPath($reservedRouteUrl)
    {
        if (! (trim($reservedRouteUrl, '/') === ''))
            return null;

        if(trim($this->current_route[0], '/') === '')
            $this->compare = true;
    }

    private function placementUrlParameters($reservedRouteUrl)
    {
        $reservedRouteUrlArray = explode('/', $reservedRouteUrl);

        if (count($this->current_route) !== count($reservedRouteUrlArray)) return;

        foreach ($this->current_route as $key => $currentRouteElement) {
            $reservedRouteUrlElement = $reservedRouteUrlArray[$key];
            if (
                substr($reservedRouteUrlElement, 0, 1) === '{'
                && substr($reservedRouteUrlElement, -1) === '}'
            )
                array_push($this->values, $currentRouteElement);
            elseif ($reservedRouteUrlElement !== $currentRouteElement)
                return;
        }

        $this->compare = true;
    }

    private function compare($reservedRouteUrl)
    {
        $this->compareRootPath($reservedRouteUrl);

        $this->placementUrlParameters($reservedRouteUrl);

        return $this->compare;
    }

    public function error404()
    {
        http_response_code(404);
        header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
        $view404 = Config::get('app.ERRORS.404');

        if (! $view404) view('errors.404');
        view($view404);

        exit;
    }
}
