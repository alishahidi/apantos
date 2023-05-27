<?php
namespace System\Router;

use System\Config\Config;
use ReflectionMethod;

class Controller
{
    private $pathControllers = '\App\Http\Controllers';

    private $match = [];

    public function __construct($match)
    {
        $this->match = $match;
    }

    public function handle()
    {
        $this->emptyMatch();

        $this->existsClass();

        $this->run();
    }

    public function run()
    {
        $class = $this->pathControllers.'\\'.$this->match['class'];
        $object = new $class();

        if (! method_exists($object, $this->match['method']))
            $this->error404();

        $reflection = new ReflectionMethod($class, $this->match['method']);
        $parameterCount = $reflection->getNumberOfParameters();

        if (! ($parameterCount <= count($this->match['parameters'])))
            $this->error404();

        call_user_func_array([$object, $this->match['method']], $this->match['parameters']);
    }

    private function emptyMatch()
    {
        if (empty($this->match)) $this->error404();
    }

    private function existsClass()
    {
        $classPath = str_replace('\\', '/', $this->match['class']);
        $path = Config::get('app.BASE_DIR').'/app/Http/Controllers/'.$classPath.'.php';

        if (! file_exists($path)) $this->error404();
    }

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