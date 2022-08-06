<?php

namespace System\Application;

use Dotenv\Dotenv;
use System\Config\Config;
use System\Security\Security;

class Application
{
    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(Config::get('app.BASE_DIR'));
        $dotenv->load();
        global $routes;
        $routes = [
            'get' => [],
            'post' => [],
            'put' => [],
            'delete' => [],
        ];
        $this->loadProviders();
        $this->loadHelpers();
        $this->loadSessions();
        $this->loadCsrf();
        $this->registerRoutes();
        $this->routing();
    }

    private function clearSetSessionService($mainSessionName, $tmpSessionName)
    {
        if (isset($_SESSION[$tmpSessionName])) {
            unset($_SESSION[$tmpSessionName]);
        }
        if (isset($_SESSION[$mainSessionName])) {
            $_SESSION[$tmpSessionName] = $_SESSION[$mainSessionName];
            unset($_SESSION[$mainSessionName]);
        }
    }

    private function initialOldSession()
    {
        $tmp = [];
        $tmp = ! isset($_GET) ? $tmp : array_merge($tmp, $_GET);
        $tmp = ! isset($_POST) ? $tmp : array_merge($tmp, $_POST);
        $_SESSION['old'] = $tmp;
        unset($tmp);
    }

    private function loadSessions()
    {
        session_start();
        $this->clearSetSessionService('old', 'tmp_old');
        $this->clearSetSessionService('flash', 'tmp_flash');
        $this->clearSetSessionService('error', 'tmp_error');
        $this->initialOldSession();
    }

    private function loadCsrf()
    {
        if (getMethod() !== 'post') {
            Security::setCsrf();
        }
    }

    private function requireFile($filePath)
    {
        $dirSep = DIRECTORY_SEPARATOR;
        $filePath = trim($filePath, ' .');
        $filePath = str_replace('.', $dirSep, $filePath);
        $filePath = Config::get('app.BASE_DIR')."{$dirSep}{$filePath}.php";
        if (file_exists($filePath)) {
            require_once $filePath;
        }
    }

    private function loadProviders()
    {
        $providers = Config::get('app.PROVIDERS');
        foreach ($providers as $provider) {
            $providerObject = new $provider();
            $providerObject->boot();
        }
    }

    private function loadHelpers()
    {
        $baseHelpers = 'system.Helpers.Helpers';
        $additionalHelpers = 'app.Http.Helpers';
        $this->requireFile($baseHelpers);
        $this->requireFile($additionalHelpers);
    }

    private function registerRoutes()
    {
        $this->requireFile('system.Helpers.Helpers');
        $this->requireFile('routes.web');
        $this->requireFile('routes.api');
    }

    private function routing()
    {
        $routing = new \System\Router\Routing();
        $routing->run();
    }
}
