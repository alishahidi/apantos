<?php

use System\Router\Web\Route;

Route::get('/captcha/get', 'Captcha\CaptchaController@get', 'captcha.get');

// editor Routes
Route::post('/file/image/upload', "File\ImageController@upload", 'file.image.upload');

// Home Routes
Route::get('/', "Home\HomeController@index", 'home.index');
Route::get('/home', "Home\HomeController@index", 'home.home');

// Auth Routes
Route::post('/login', "Auth\LoginController@login", 'auth.login');
Route::post('/register', "Auth\RegisterController@register", 'auth.register');
Route::get('/logout', "Auth\LogoutController@logout", 'auth.logout');
