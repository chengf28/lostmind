<?php

use Core\Facade\Route;


Route::post('/', 'TestController@test');

Route::get('test/{username}/{id}', 'TestController@test2')->where('username','[A-Za-z]+')->where('id','[0-9]+');
