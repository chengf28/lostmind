<?php

use Core\Facade\App;
use Core\Facade\View;

/**
 * 获取View类
 */
if (!function_exists('view')) {
    function view($path, $data = null)
    {
        if ($data) {
            return View::with($data)->show($path);
        }
        return View::show($path);
    }
}

/**
 * 获取Request 类
 */
if (!function_exists('request')) {
    function request($key = null)
    {
        $request = App::make('Request');
        if ($key) {
            return $request[$key];
        }
        return $request;
    }
}


if (!function_exists('App')) {
    function App($key = null)
    {
        $app = App::make('app');
        if ($key) {
            return $app->getConfig('base.'.$key);
        }
        return $app;
    }
}