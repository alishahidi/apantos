<?php

namespace System\Ftp\Traits;

use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToCopyFile;
use League\Flysystem\UnableToCreateDirectory;
use League\Flysystem\UnableToDeleteDirectory;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToMoveFile;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToWriteFile;
use System\Ftp\FtpConnection\FtpConnection;

trait HasClient
{
    public static function put($path, $content)
    {
        try {
            $filesystem = FtpConnection::getFtpConnectionInstance();
            $filesystem->write($path, $content);

            return '/'.trim(str_replace(DIRECTORY_SEPARATOR, '/', $path), "\/");
        } catch (FilesystemException | UnableToWriteFile $exception) {
            return false;
        }
    }

    public static function read($path)
    {
        try {
            $filesystem = FtpConnection::getFtpConnectionInstance();
            $content = $filesystem->read($path);

            return $content;
        } catch (FilesystemException | UnableToReadFile $exception) {
            return false;
        }
    }

    public static function deleteDirectory($path)
    {
        try {
            $filesystem = FtpConnection::getFtpConnectionInstance();
            $filesystem->writedeleteDirectory($path);

            return true;
        } catch (FilesystemException | UnableToDeleteDirectory $exception) {
            return false;
        }
    }

    public static function createDirectory($path)
    {
        try {
            $filesystem = FtpConnection::getFtpConnectionInstance();
            $filesystem->createDirectory($path);

            return true;
        } catch (FilesystemException | UnableToCreateDirectory $exception) {
            return false;
        }
    }

    public static function delete($path)
    {
        try {
            $filesystem = FtpConnection::getFtpConnectionInstance();
            $filesystem->delete($path);

            return true;
        } catch (FilesystemException | UnableToDeleteFile $exception) {
            return false;
        }
    }

    public static function move($from, $to)
    {
        try {
            $filesystem = FtpConnection::getFtpConnectionInstance();
            $filesystem->move($from, $to);

            return true;
        } catch (FilesystemException | UnableToMoveFile $exception) {
            return false;
        }
    }

    public static function copy($from, $to)
    {
        try {
            $filesystem = FtpConnection::getFtpConnectionInstance();
            $filesystem->copy($from, $to);

            return true;
        } catch (FilesystemException | UnableToCopyFile $exception) {
            return false;
        }
    }
}
