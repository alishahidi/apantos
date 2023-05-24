<?php
namespace System\Router;

class Compare
{
    private $reserved;

    private $currentRoute;

    private $result = false;

    //TODO: Not related to compare
    private $values = [];

    public function __construct($reserved, $currentRoute)
    {
        $this->reserved = $reserved;
        $this->currentRoute = $currentRoute;
    }

    public function compare()
    {
        $this->compareRootPath();

        $this->placementUrlParameters();

        return $this->result;
    }

    private function compareRootPath()
    {
        if (! (trim($this->reserved, '/') === ''))
            return null;

        if(trim($this->currentRoute[0], '/') === '')
            $this->result = true;
    }

    private function placementUrlParameters()
    {
        $reservedRouteUrlArray = explode('/', $this->reserved);

        if (count($this->currentRoute) !== count($reservedRouteUrlArray)) return;

        //TODO this is dirty code and not related to compare class
        foreach ($this->currentRoute as $key => $currentRouteElement) {
            $reservedRouteUrlElement = $reservedRouteUrlArray[$key];
            if (
                substr($reservedRouteUrlElement, 0, 1) === '{'
                && substr($reservedRouteUrlElement, -1) === '}'
            )
                array_push($this->values, $currentRouteElement);
            elseif ($reservedRouteUrlElement !== $currentRouteElement)
                return;
        }
        //TODO

        $this->result = true;
    }

    //TODO: not related to compare
    public function values()
    {
        return $this->values;
    }
}