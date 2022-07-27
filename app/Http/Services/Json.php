<?php

namespace App\Http\Services;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Filesystem\Filesystem;

class Json
{
    public static function write($dir, $content)
    {
        $dirSep = DIRECTORY_SEPARATOR;
        $path = "jsons{$dirSep}{$dir}{$dirSep}";
        $uuid = Uuid::uuid4()->toString();
        $name = date('Y_m_d_M_i_s_').$uuid.'.json';
        if (! is_dir($path)) {
            if (! mkdir($path, recursive: true)) {
                dd('Faild to create directory.');
            }
        }
        if (! is_writable($path)) {
            dd('Directory not writable');
        }
        $fullPath = $path.$name;
        $filesystem = new Filesystem();
        $filesystem->dumpFile($fullPath, json_encode($content, JSON_UNESCAPED_UNICODE));

        return '/'.$fullPath;
    }

    public static function read($path)
    {
        $dirSep = DIRECTORY_SEPARATOR;
        $path = str_replace("\/", $dirSep, $path);
        $path = trim($path, DIRECTORY_SEPARATOR);

        return json_decode(file_get_contents($path));
    }
}
