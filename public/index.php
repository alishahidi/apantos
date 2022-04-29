<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$dirSep = DIRECTORY_SEPARATOR;
$baseDir = require_once (dirname(__DIR__)."{$dirSep}vendor{$dirSep}autoload.php");
require_once (dirname(__DIR__)."/bootstrap/Bootstrap.php");
new \Bootstrap\Bootstrap();