<?php

namespace System\Config;

use Symfony\Component\Finder\Finder;

class Config
{
    private static $instance;
    private $config_nested_array = [];
    private $config_dot_array = [];

    private function __construct()
    {
        $this->initialConfigArray();
    }

    private function initialConfigArray()
    {
        $dirSep = DIRECTORY_SEPARATOR;
        $configPath = dirname(dirname(__DIR__)) . "{$dirSep}config{$dirSep}";
        $finder = new Finder();
        $finder->files()->in($configPath);
        foreach ($finder as $file) {
            $config = require $file->getRealPath();
            $key = str_replace([$configPath, ".php"], "", $file->getRealPath());
            $this->config_nested_array[$key] = $config;
        }
        $this->initialDefaultValues();
    }

    private function initialDefaultValues()
    {
        $tmp = str_replace($this->config_nested_array["app"]["BASE_URL"], "", explode("?", $_SERVER["REQUEST_URI"])[0]);
        $tmp === "/" ? $tmp = "" : $tmp = substr($tmp, 1);
        $this->config_nested_array["app"]["CURRENT_ROUTE"] = $tmp;
        unset($tmp);
    }

    private static function getInstance()
    {
        if (empty(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

    public static function get($key)
    {
        if (!strpos($key, "."))
            return env($key);
        $instance = self::getInstance();
        $dot = new \System\Dot\Dot($instance->config_nested_array);
        return $dot->get($key);
    }
}
