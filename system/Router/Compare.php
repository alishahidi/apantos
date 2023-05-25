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

        $this->checkEqualUrl();
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

    private function checkEqualUrl()
    {
        $reservedRouteUrlArray = $this->explodeSlash();

        if (count($this->currentRoute) !== count($reservedRouteUrlArray)) return;

        if(array_diff( $this->currentRoute, $reservedRouteUrlArray )) return;

        $this->result = true;
    }
}