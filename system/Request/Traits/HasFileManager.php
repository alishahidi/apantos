<?php

namespace System\Request\Traits;

use Intervention\Image\ImageManager;

trait HasFileManager
{
    public function uploadImage($fileName, $path, $name, $widthHeight = [])
    {
        $file = $this->file($fileName);
        if (!$file["tmp_name"])
            return false;
        $path = trim($path, "\/") . DIRECTORY_SEPARATOR;
        $name = trim($name, "\/") . "." . pathinfo($file["name"], PATHINFO_EXTENSION);
        if (!is_dir($path))
            if (!mkdir($path, recursive: true))
                dd("Faild to create directory.");
        if (!is_writable($path))
            dd("Directory not writable");
        $manager = new ImageManager(["driver" => "gd"]);
        if (!empty($widthHeight))
            $image = $manager->make($file["tmp_name"])->fit($widthHeight[0], $widthHeight[1]);
        else
            $image = $manager->make($file["tmp_name"]);
        $image->save($path . $name);
        return DIRECTORY_SEPARATOR . $path . $name;
    }
}
