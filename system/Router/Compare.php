<?php
namespace System\Router;

class Compare
{
    private $reserved;

    private $currentRoute;

    public function __construct($reserved, $currentRoute)
    {
        $this->reserved = $reserved;
        $this->currentRoute = $currentRoute;
    }

    public function handle()
    {
        if(($this->rootPath() || $this->subPath())) return true;
    }

    private function trimEqual($first, $second)
    {
        return ((trim($first, '/') === '') && (trim($second, '/') === ''));
    }

    private function countEqual($first, $second)
    {
        return count($first) === count($second);
    }

    private function rootPath()
    {
        if($this->trimEqual($this->currentRoute[0], $this->reserved))
            return true;
    }

    private function checkArguaments($reserve, $item)
    {
        return (! $this->existArguments($reserve)) && ($item != $reserve);
    }

    private function subPath()
    {
        $reservedRouteUrlArray = explode('/', $this->reserved);

        if ($this->countEqual($this->currentRoute, $reservedRouteUrlArray))
            return $this->resolveArguments($reservedRouteUrlArray);
    }

    public function resolveArguments($reservedRouteUrlArray)
    {
        foreach($this->currentRoute as $key => $item)
        {
            if(! $this->checkArguaments($reservedRouteUrlArray[$key], $item)) continue;

            return;
        };

        return true;
    }

    private function existArguments($reserve)
    {
        return (substr($reserve, 0, 1) === '{' && substr($reserve, -1) === '}');
    }
}