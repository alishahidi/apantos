<?php

use System\Router\Api\Route;


Route::get("/token", "Api\TokenController@get", "token.get");
