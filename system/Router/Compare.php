<?php
namespace System\Router;

class Compare
{
    private $reserved;

    private $currentRoute;

    private $result = false;

    public function __construct($reserved, $currentRoute)
    {
        $this->reserved = $reserved;
        $this->currentRoute = $currentRoute;
    }

    public function get()
    {
        $this->handle();

        return $this->result;
    }

    private function handle()
    {
        $this->rootPath();

        $this->subPath();
    }

    private function trimRootPath($value)
    {
        return (trim($value, '/') === '');
    }

    private function rootPath()
    {
        if($this->trimRootPath($this->currentRoute[0]) &&
            $this->trimRootPath($this->reserved))
            $this->result = true;
    }

    private function explodeSlash()
    {
        return explode('/', $this->reserved);
    }

    private function subPath()
    {
        $reservedRouteUrlArray = $this->explodeSlash();

        if (count($this->currentRoute) !== count($reservedRouteUrlArray)) return;

        $this->checkSubPath($reservedRouteUrlArray);
    }

    private function checkSubPath($reservedRouteUrlArray)
    {
        foreach($this->currentRoute as $key => $item)
        {
            $reserve = $reservedRouteUrlArray[$key];
            if($this->existArguments($reserve)) continue;
            if($item != $reserve) return;
        };

        $this->result = true;
    }

    private function existArguments($reserve)
    {
        if(substr($reserve, 0, 1) === '{' &&
            substr($reserve, -1) === '}')
            return true;
    }
}