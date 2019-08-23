<?php

use Core\Facade\Route;


Route::get('/', 'TestController@test');

Route::post('/','TestController@test2');

Route::get('test/{username}/{id}', 'TestController@test2')->where('username','[A-Za-z]+')->where('id','[0-9]+');
