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

    private function compare()
    {
        $this->compareRootPath();

        if(! $this->checkCount())
            return;

        $this->checkEqualUrl();
    }

    private function checkTrimRootPath($value)
    {
        return (trim($value, '/') === '');
    }

    private function compareRootPath()
    {
        if($this->checkTrimRootPath($this->currentRoute[0]) &&
            $this->checkTrimRootPath($this->reserved))
            $this->result = true;
    }

    private function checkCount()
    {
        $reservedRouteUrlArray = $this->explodeSlash();

        if (count($this->currentRoute) !== count($reservedRouteUrlArray))
            return false;

        return true;
    }

    private function explodeSlash()
    {
        return explode('/', $this->reserved);
    }

    private function checkEqualUrl()
    {
        $reservedRouteUrlArray = $this->explodeSlash();

        if(! array_diff($this->currentRoute, $reservedRouteUrlArray))
            $this->result = true;
    }

    public function get()
    {
        $this->compare();

        return $this->result;
    }
}