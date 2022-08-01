<?php

namespace System\Image\Traits;

use Intervention\Image\Gd\Font;
use Intervention\Image\ImageManager;
use Ramsey\Uuid\Nonstandard\Uuid;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use System\Ftp\Ftp;
use System\Request\Request;

trait HasBuilder
{
    private $driver;

    private $directory;

    protected function makeMethod($name, $directory = '', $dateFormat = false)
    {
        $request = new Request();
        $file = $request->file($name);

        if (! $file['tmp_name']) {
            return false;
        }
        $filesystem = new Filesystem();
        if (isset($directory)) {
            $directory = trim($directory, "\/").'/';
        }
        if ($dateFormat) {
            $directory .= date('Y/M/d/');
        }
        $this->directory = $directory;
        $filesystem->mkdir(Path::normalize($directory));
        $this->driver = (new ImageManager(['driver' => 'gd']))->make($file['tmp_name']);
        $this->setAllowedMethods(['watermark', 'text', 'resize', 'fit', 'save', 'saveFtp']);

        return $this;
    }

    protected function resizeMethod($width, $height)
    {
        $this->driver->resize($width, $height);
        $this->setAllowedMethods(['watermark', 'text', 'fit', 'save', 'saveFtp']);

        return $this;
    }

    protected function fitMethod($width, $height)
    {
        $this->driver->fit($width, $height);
        $this->setAllowedMethods(['watermark', 'text', 'resize', 'save', 'saveFtp']);

        return $this;
    }

    protected function watermarkMethod($path, $width, $height, $pos = 'bottom-right', $x = 20, $y = 20)
    {
        $_driver = (new ImageManager(['driver' => 'gd']))->make($path);
        $_driver->resize($width, $height);
        $this->driver->insert($_driver, $pos, $x, $y);

        $this->setAllowedMethods(['text', 'resize', 'fit', 'save', 'saveFtp']);

        return $this;
    }

    protected function textMethod($text, $x = 20, $y = 20, $fontFile = 'fonts/Roboto-Regular.ttf', $size = 24, $color = '#ffffff', $pos = 'bottom-right', $angle = 0)
    {
        $bbox = imagettfbbox($size, $angle, $fontFile, $text);
        $width = abs($bbox[2] - $bbox[0]) - 30;
        $height = abs($bbox[7] - $bbox[1]);
        $font = new Font($text);
        $font->file($fontFile);
        $font->size($size);
        $font->color($color);
        $font->valign('top');
        $font->angle($angle);
        $_driver = (new ImageManager(['driver' => 'gd']));
        $imageText = $_driver->canvas($width, $height);
        $font->applyToImage($imageText);
        $this->driver->insert($imageText, $pos, $x, $y);

        $this->setAllowedMethods(['watermark', 'resize', 'fit', 'save', 'saveFtp']);

        return $this;
    }

    protected function encodeMethod($quality = 42, $format = 'jpg')
    {
        return $this->driver->encode($format, $quality);
    }

    protected function saveMethod($name = '', $quality = 42, $format = 'jpg', $unique = false, $dateFormat = false)
    {
        $name = explode('.', $name)[0];
        if ($dateFormat) {
            $name .= date('Y_m_d_M_i_s');
        }
        if ($unique) {
            $name .= '_'.Uuid::uuid4()->toString();
        }
        $this->driver->save($this->directory.$name.'.'.$format, $quality, $format);

        return '/'.$this->directory.$name.'.'.$format;
    }

    protected function saveFtpMethod($name = '', $quality = 42, $format = 'jpg', $unique = false, $dateFormat = false)
    {
        $name = explode('.', $name)[0];
        if ($dateFormat) {
            $name .= date('Y_m_d_M_i_s');
        }
        if ($unique) {
            $name .= '_'.Uuid::uuid4()->toString();
        }

        return Ftp::put($this->directory.$name.'.'.$format, $this->encodeMethod($quality, $format));
    }
}
