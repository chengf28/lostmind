<?php

use Core\Facade\Route;

Route::get('test','Controller@test');

Route::get('test/{id}','Controller@testc');