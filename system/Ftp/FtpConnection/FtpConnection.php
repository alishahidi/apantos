<?php

namespace System\Ftp\FtpConnection;

use League\Flysystem\Filesystem;
use League\Flysystem\Ftp\FtpAdapter;
use League\Flysystem\Ftp\FtpConnectionOptions;
use System\Config\Config;

class FtpConnection
{
    private static $filesystem = null;

    private function __construct()
    {
    }

    public static function getFtpConnectionInstance()
    {
        if (self::$filesystem == null) {
            $connection = new FtpConnection();
            self::$filesystem = $connection->getFilesystem();
        }

        return self::$filesystem;
    }

    private function getFilesystem()
    {
        return new Filesystem(new FtpAdapter(
            FtpConnectionOptions::fromArray([
                'host' => Config::get('FTP_HOST'),
                'root' => Config::get('FTP_ROOT'),
                'username' => Config::get('FTP_USERNAME'),
                'password' => Config::get('FTP_PASSWORD'),
                'port' => (int) Config::get('FTP_PORT'),
                'ssl' => false,
                'timeout' => (int) Config::get('FTP_TIMEOUT'),
                'utf8' => false,
                'passive' => true,
                'transferMode' => FTP_BINARY,
                'systemType' => null,
                'ignorePassiveAddress' => null,
                'timestampsOnUnixListingsEnabled' => false,
                'recurseManually' => true,
            ])
        ));
    }
}
