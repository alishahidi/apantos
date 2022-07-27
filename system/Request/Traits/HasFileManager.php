<?php

namespace System\Request\Traits;

use Intervention\Image\ImageManagerStatic as Image;
use System\Config\Config;
use System\Ftp\Ftp;

trait HasFileManager
{
    // public function createDir($path)
    // {
    //     $basePath = Config::get("app.BASE_DIR") .  DIRECTORY_SEPARATOR;
    //     $path = trim(str_replace("/", DIRECTORY_SEPARATOR, $path), "/");
    //     $fullPath = $basePath . $path;
    //     $filesystem = new Filesystem();
    //     try {
    //         $filesystem->mkdir($fullPath);
    //         return true;
    //     } catch (IOExceptionInterface $exception) {
    //         return false;
    //     }
    // }

    // public function removeFile($path)
    // {
    //     $basePath = Config::get("app.BASE_DIR") .  DIRECTORY_SEPARATOR;
    //     $path = trim(str_replace("/", DIRECTORY_SEPARATOR, $path), "/");
    //     $fullPath = $basePath . $path;
    //     $filesystem = new Filesystem();
    //     try {
    //         $filesystem->remove($fullPath);
    //         return true;
    //     } catch (IOExceptionInterface $exception) {
    //         return false;
    //     }
    // }

    public function uploadImage($fileName, $path, $name, $widthHeight = [], $watermark = null)
    {
        $file = $this->file($fileName);
        if (!$file["tmp_name"])
            return false;
        $path = trim($path, "\/") . DIRECTORY_SEPARATOR;
        $name = trim($name, "\/") . "." . "jpg";
        if (!is_dir($path))
            if (!mkdir($path, recursive: true))
                dd("Faild to create directory.");
        if (!is_writable($path))
            dd("Directory not writable");
        Image::configure(["driver" => "gd"]);
        if (!empty($widthHeight))
            $image = Image::make($file["tmp_name"])->fit($widthHeight[0], $widthHeight[1]);
        else
            $image = Image::make($file["tmp_name"]);

        if ($watermark) {
            $dirsep = DIRECTORY_SEPARATOR;
            $watermarkImage = Image::make(Config::get("app.BASE_DIR") . "{$dirsep}storage{$dirsep}" . $watermark["file"])->fit($watermark["width"], $watermark["height"]);
            $image->insert($watermarkImage, $watermark["pos"], $watermark["x"], $watermark["y"]);
        }
        $image->save($path . $name, Config::get("image.QUALITY"), "jpg");
        return DIRECTORY_SEPARATOR . $path . $name;
    }

    public function uploadImageFtp($fileName, $path, $name, $widthHeight = [], $watermark = null)
    {
        $file = $this->file($fileName);
        if (!$file["tmp_name"])
            return false;
        $path = trim($path, "\/") . DIRECTORY_SEPARATOR;
        $name = trim($name, "\/") . "." . pathinfo($file["name"], PATHINFO_EXTENSION);
        Image::configure(["driver" => "gd"]);
        if (!empty($widthHeight))
            $image = Image::make($file["tmp_name"])->fit($widthHeight[0], $widthHeight[1]);
        else
            $image = Image::make($file["tmp_name"]);

        if ($watermark) {
            $dirsep = DIRECTORY_SEPARATOR;
            $watermarkImage = Image::make(Config::get("app.BASE_DIR") . "{$dirsep}storage{$dirsep}" . $watermark["file"])->fit($watermark["width"], $watermark["height"]);
            $image->insert($watermarkImage, $watermark["pos"], $watermark["x"], $watermark["y"]);
        }
        $imageContent = $image->encode("jpg", Config::get("image.QUALITY"));
        return Ftp::put($path . $name, $imageContent);
    }
}
