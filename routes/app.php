<?php

use Core\Facade\Route;

// Route::get('test','Controller@test');

// Route::get('test/{id}','Controller@testc')->where('id','[0-9]+');
Route::get('test/{username}/{id}','Controller@testc')->where('username','[A-Za-z]+')->where('id','[0-9]+');