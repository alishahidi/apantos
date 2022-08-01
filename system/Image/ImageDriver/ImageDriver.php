<?php

namespace System\Image\ImageDriver;

use Intervention\Image\ImageManager;

class ImageDriver
{
    private static $driver = null;

    private function __construct()
    {
    }

    public static function getImageDriverInstance()
    {
        if (self::$driver == null) {
            $connection = new ImageDriver();
            self::$driver = $connection->getDriver();
        }

        return self::$driver;
    }

    private function getDriver()
    {
        return new ImageManager(['driver' => 'gd']);
    }
}
