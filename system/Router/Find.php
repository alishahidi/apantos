<?php
namespace System\Router;

use System\Router\Compare;

class Find
{
    private $reserves;

    private $currentRoute;

    private $parameters = [];

    public function __construct($reserves, $currentRoute)
    {
        $this->reserves = $reserves;
        $this->currentRoute = $currentRoute;
    }

    public function handle()
    {
        $result = [];

        foreach($this->reserves as $reserve)
        {
            $reserveUrl = $reserve['url'];

            if($this->checkCompare($reserveUrl)){
                $this->resolveParameters($reserveUrl);
                $result = array_merge($reserve, ['parameters' => $this->parameters]);
            };
        }

        return  $result;
    }

    private function checkCompare($reserveUrl)
    {
        return (new Compare($reserveUrl, $this->currentRoute))
            ->get();
    }

    private function resolveParameters($reserve)
    {
        if(! ((strpos($reserve, '{')) || strpos($reserve, '}')) ) return;

        $reserveExplode = explode('/', $reserve);

        foreach($this->currentRoute as $key => $item)
        {
            $reserveKey = $reserveExplode[$key];

            if(!(substr($reserveKey, 0, 1) === '{'
                && substr($reserveKey, -1) === '}'))
                continue;

            array_push($this->parameters, $item);
        }
    }
}