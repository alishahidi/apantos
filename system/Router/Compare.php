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

    private function compareRootPath()
    {
        if (! (trim($this->reserved, '/') === ''))
            return null;

        if(trim($this->currentRoute[0], '/') === '')
            $this->result = true;
    }

    private function checkCount()
    {
        $reservedRouteUrlArray = explode('/', $this->reserved);
        if (count($this->currentRoute) !== count($reservedRouteUrlArray))
            return false;

        return true;
    }

    private function checkEqualUrl()
    {
        $reservedRouteUrlArray = explode('/', $this->reserved);

        if(! array_diff($this->currentRoute, $reservedRouteUrlArray))
            $this->result = true;
    }

    public function get()
    {
        $this->compare();

        return $this->result;
    }
}