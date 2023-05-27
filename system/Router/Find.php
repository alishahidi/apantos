<?php
namespace System\Router;

use System\Router\Compare;

class Find
{
    private $reserves;

    private $currentRoute;

    private $parameters = [];

    private $result = [];

    public function __construct($reserves, $currentRoute)
    {
        $this->reserves = $reserves;
        $this->currentRoute = $currentRoute;
    }

    public function handle()
    {
        foreach($this->reserves as $reserve)
        {
            $this->resolve($reserve);
        }

        return $this->result;
    }

    private function resolve($reserve)
    {
        $reserveUrl = $reserve['url'];

        if(! $this->checkCompare($reserveUrl)) return null;

        $this->resolveParameters($reserveUrl);
        $this->merge($reserve);
    }

    private function merge($reserve)
    {
        $this->result =  array_merge($reserve, ['parameters' => $this->parameters]);
    }

    private function checkCompare($reserveUrl)
    {
        return (new Compare($reserveUrl, $this->currentRoute))
            ->get();
    }

    private function resolveParameters($reserve)
    {
        if(! $this->existParam($reserve) ) return;

        foreach($this->currentRoute as $key => $item)
        {
            $this->addParam((explode('/', $reserve))[$key], $item);
        }
    }

    private function existParam($reserve)
    {
        return ((strpos($reserve, '{')) || strpos($reserve, '}'));
    }

    private function addParam($reserve, $item)
    {
        if($this->checkLocationParam($reserve))
            array_push($this->parameters, $item);
    }

    private function checkLocationParam($reserve)
    {
        return (substr($reserve, 0, 1) === '{' && substr($reserve, -1) === '}');
    }
}