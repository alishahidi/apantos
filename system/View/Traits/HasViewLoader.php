<?php

namespace  System\View\Traits;

use System\Config\Config;

trait HasViewLoader
{
    private $viewNameArray = [];

    private function viewLoader($filePath)
    {
        $dirSep = DIRECTORY_SEPARATOR;
        $baseDir = Config::get('app.BASE_DIR')."{$dirSep}resources{$dirSep}view{$dirSep}";
        $filePath = trim($filePath, ' .');
        $filePath = str_replace('.', DIRECTORY_SEPARATOR, $filePath);

        if (file_exists("{$baseDir}$filePath.apts.php")) {
            $this->registerView($filePath);
            $content = htmlentities(file_get_contents("{$baseDir}$filePath.apts.php"));
            $this->isApts = true;
        } elseif (file_exists("{$baseDir}$filePath.php")) {
            $this->registerView($filePath);
            $content = htmlentities(file_get_contents("{$baseDir}$filePath.php"));
            $this->isApts = false;
        } else {
            throw new \Exception('view not Found.', 404);

            return false;
        }

        return $content;
    }

    private function registerView($view)
    {
        array_push($this->viewNameArray, $view);
    }
}
