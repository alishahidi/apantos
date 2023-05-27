<?php
namespace System\Router;

use System\Config\Config;
use ReflectionMethod;

class Controller
{
    private $nameSpaceControllers = '\App\Http\Controllers';

    private $match = [];

    public function __construct($match)
    {
        $this->match = $match;
    }

    public function handle()
    {
        if(! $this->run() === false) $this->error404();;
    }

    //TODO The checkEmpty method is messy
    private function checkEmpty()
    {
        return ((empty($this->match)));
    }

    private function existFile()
    {
        return (file_exists($this->pathClass()));
    }

    private function replace()
    {
        return str_replace('\\', '/', $this->match['class']);
    }

    private function pathClass()
    {
        return Config::get('app.BASE_DIR').'/app/Http/Controllers/'.$this->replace().'.php';
    }

    private function setObject()
    {
        $class = $this->nameSpaceControllers.'\\'.$this->match['class'];

        return new $class();
    }

    private function existMethod($object)
    {
        return (method_exists($object, $this->match['method']));
    }

    private function resolveReflaction($object)
    {
        $reflection = new ReflectionMethod($object, $this->match['method']);

        return  $reflection->getNumberOfParameters();
    }

    private function checkCountParameters($count)
    {
        return ($count <= count($this->match['parameters']));
    }

    private function contains()
    {
        if($this->checkEmpty()
        || ! $this->existFile()
        || ! $this->existMethod($object = $this->setObject())
        || ! $this->checkCountParameters($this->resolveReflaction($object)))
            return false;
    }

    //TODO: dirty code
    public function run()
    {
        if($this->contains()) return false;

        call_user_func_array([$this->setObject(), $this->match['method']], $this->match['parameters']);
    }

    //TODO The 404 method is messy
    private function error404()
    {
        http_response_code(404);
        header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
        $view404 = Config::get('app.ERRORS.404');

        if (! $view404) view('errors.404');
        view($view404);

        exit;
    }
}