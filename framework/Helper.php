<?php

use Core\Facade\App;
use Core\Facade\View;

if (!function_exists('view')) {
    /**
     * 获取View类
     * @param string $path
     * @param array $data
     */
    function view(string $path,array $data = null)
    {
        if ($data) {
            return View::with($data)->show($path);
        }
        return View::show($path);
    }
}

if (!function_exists('request')) {
    /**
     * 获取Request 类
     * @param string $key
     */
    function request(string $key = null)
    {
        $request = App::make('Request');
        if ($key) {
            return $request[$key];
        }
        return $request;
    }
}

if (!function_exists('config')) {
    /**
     * 读取参数
     * @param string $key
     */
    function config(string $key)
    {
        return App::getConfig($key);    
    }
}