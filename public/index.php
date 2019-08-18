<?php

use Core\Facade\Route;
use DBquery\DBquery;

// 设置项目根路径
defined("__ROOT__") or define("__ROOT__",dirname(__DIR__));
require_once __ROOT__.DIRECTORY_SEPARATOR.'bootstrap'.DIRECTORY_SEPARATOR.'bootstrap.php';


Route::get('test/{id}/{test}','TestController@test');