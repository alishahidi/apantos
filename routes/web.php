<?php

use System\Router\Web\Route;

Route::get('/captcha/get', 'Captcha\CaptchaController@get', 'captcha.get');

// editor Routes
Route::post("/file/image/upload", "File\ImageController@upload", "file.image.upload");

// Home Controller
Route::get("/", "Home\HomeController@index", "home.index");