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
        if($this->run() === false) ErrorHandler::handle(404);
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

        return true;
    }

    //TODO: dirty code
    public function run()
    {
        if(!($this->contains())) return false;

        call_user_func_array([$this->setObject(), $this->match['method']], $this->match['parameters']);
    }
}