<?php

namespace Core\Request;

use Core\Route\RouteCollection;
use Core\Exception\Request\PageNotFoundException;
use Core\Exception\Request\RouteArgumentException;
use ReflectionMethod;

class Request
{
    protected $method;

    protected $uri;

    protected $routes;

    protected $app;

    public function __construct(\Core\Application $app, RouteCollection $routeCollection)
    {
        $this->app    = $app;
        $this->routes = $routeCollection;
        $this->uri    = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * 网络请求
     * @return void
     * @throws \Core\Exception\Request\PageNotFoundException|\Core\Exception\Request\RouteArgumentException
     * Real programmers don't read comments, novices do
     */
    public function send()
    {
        // 无参数匹配类型路由
        $route = $this->routes->staticMatch($this->method, $this->uri);
        if (!$route) {
            // 有参数类型匹配路由
            $route = $this->routes->match($this->method, $this->uri);
            if (!$route) {
                throw new PageNotFoundException($this->uri);
            }
        }
        $controllerData = $route->controller();
        if (strpos($controllerData, '@') === false) {
            throw new RouteArgumentException;
        }
        list($controller, $method) = explode('@', $controllerData);
        $controller = 'App\Controllers\\' . $controller;
        $class = new ReflectionMethod($controller, $method);
        $with = [];
        foreach ($class->getParameters() as $param) {
            if($argClass = $param->getClass())
            {
                if($argClassWith = $route->getParams($argClass->name))
                {
                    $with[] = $this->app->makeWith($argClass->name,(array)$argClassWith);
                }else{
                    $with[] = $this->app->make($argClass->name);
                }
            }else{
                $with[] = $route->getParams($param->name);
            }
            
        }
        $class->invokeArgs($this->app[$controller],$with);
    }
}
