<?php

use Core\Facade\Route;

Route::get('test', 'TestController@test');

Route::post('test/{username}/{id}', 'TestController@test2')
->where('username','[A-Za-z]+')->where('id','[0-9]+');