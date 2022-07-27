<?php

namespace App\Providers;

class SessionServiceProvider extends Provider
{
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

    public function boot()
    {
        session_start();
        $this->clearSetSessionService('old', 'tmp_old');
        $this->clearSetSessionService('flash', 'tmp_flash');
        $this->clearSetSessionService('error', 'tmp_error');
        $this->initialOldSession();
    }
}
