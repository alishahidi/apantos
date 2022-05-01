<?php

namespace App\Http\Services;

use Ramsey\Uuid\Uuid;
use System\Config\Config;
use System\Request\Request;

class ImageUpload
{
    public static function dateFormatUploadAndFit($fileName, $dir, $width, $height, $watermark = null)
    {
        $request = new Request();
        $dirSep = DIRECTORY_SEPARATOR;
        $path = "images{$dirSep}{$dir}{$dirSep}" . date("Y{$dirSep}M{$dirSep}d");
        $uuid = Uuid::uuid4()->toString();
        $name = date("Y_m_d_M_i_s_") . $uuid;
        if ($watermark)
            return $request->uploadImage($fileName, $path, $name, [$width, $height], $watermark);
        return $request->uploadImage($fileName, $path, $name, [$width, $height]);
    }

    public static function uploadAndFit($fileName, $dir, $width, $height, $watermark = null)
    {
        $request = new Request();
        $dirSep = DIRECTORY_SEPARATOR;
        $path = "images{$dirSep}{$dir}{$dirSep}";
        $uuid = Uuid::uuid4()->toString();
        $name = date("Y_m_d_M_i_s_") . $uuid;
        if ($watermark)
            return $request->uploadImage($fileName, $path, $name, [$width, $height], $watermark);
        return $request->uploadImage($fileName, $path, $name, [$width, $height]);
    }

    public static function dateFormatUploadEditor($fileName)
    {
        $request = new Request();
        $dirSep = DIRECTORY_SEPARATOR;
        $path = "editor{$dirSep}" . date("Y{$dirSep}M{$dirSep}d");
        $uuid = Uuid::uuid4()->toString();
        $name = date("Y_m_d_M_i_s_") . $uuid;
        return $request->uploadImage($fileName, $path, $name);
    }
}
