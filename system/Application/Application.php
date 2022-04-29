<?php

namespace System\Application;

use Dotenv\Dotenv;
use System\Config\Config;

class Application
{
    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(Config::get("app.BASE_DIR"));
        $dotenv->load();
        error_reporting(1);
        global $routes;
        $routes = [
            "get" => [],
            "post" => [],
            "put" => [],
            "delete" => []
        ];
        $this->loadProviders();
        $this->loadHelpers();
        $this->registerRoutes();
        $this->routing();
    }

    private function requireFile($filePath)
    {
        $dirSep = DIRECTORY_SEPARATOR;
        $filePath = trim($filePath, " .");
        $filePath = str_replace(".", $dirSep, $filePath);
        $filePath = Config::get("app.BASE_DIR") . "{$dirSep}{$filePath}.php";
        if (file_exists($filePath))
            require_once($filePath);
    }


    private function loadProviders()
    {
        $providers = Config::get("app.PROVIDERS");
        foreach ($providers as $provider) {
            $providerObject = new $provider();
            $providerObject->boot();
        }
    }

    private function loadHelpers()
    {
        $baseHelpers = "system.Helpers.Helpers";
        $additionalHelpers = "app.Http.Helpers";
        $this->requireFile($baseHelpers);
        $this->requireFile($additionalHelpers);
    }

    private function registerRoutes()
    {
        $this->requireFile("system.Helpers.Helpers");
        $this->requireFile("routes.web");
        $this->requireFile("routes.api");
    }

    private function routing()
    {
        $routing = new \System\Router\Routing();
        $routing->run();
    }
}
