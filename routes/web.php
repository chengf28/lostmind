<?php

use Core\Facade\Route;


Route::get('/', 'TestController@test');

Route::get('test/{username}/{id}', 'TestController@test2')
->where('username','[A-Za-z]+')->where('id','[0-9]+');