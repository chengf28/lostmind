<?php

namespace Core\Request;

use Core\Application;
use Core\Route\RouteCollection;
use Core\Exception\Request\PageNotFoundException;
use Core\Exception\Request\RouteArgumentException;
use ReflectionMethod;

class Request implements \ArrayAccess, \Iterator
{
    protected $method;

    protected $uri;

    protected $routes;

    protected $app;

    protected $namespace;

    protected $RequestParams;

    public function __construct(Application $app, RouteCollection $routeCollection)
    {
        $this->app       = $app;
        $this->routes    = $routeCollection;
        $this->uri       = $_SERVER['SCRIPT_NAME'];
        $this->method    = $_SERVER['REQUEST_METHOD'];
        $this->namespace = 'App\Controllers\\';
        $args            = $_REQUEST;
        if (empty($args))
        {
            $raw  = file_get_contents('php://input');
            if (!empty($raw)) 
            {
                $args = (array)$raw;
            }
        }
        $this->RequestParams = $args;
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
                // 抛出异常
                throw new PageNotFoundException();
            }
        }
        $controllerData = $route->controller();
        if (strpos($controllerData, '@') === false) {
            throw new RouteArgumentException;
        }
        list($controller, $method) = explode('@', $controllerData);
        $controller                = $this->namespace . $controller;
        $class                     = new ReflectionMethod($controller, $method);
        $with                      = [];
        /**
         * 获取到该方法是否需要参数
         */
        foreach ($class->getParameters() as $param) {
            // 获取参数类型是否为一个Class
            if ($argClass = $param->getClass()) {
                // 如果有class类型的参数,且在uri中带有参数值,则带有参数的去实例化,否则直接获取实例
                $with[] = $this->app->makeWith($argClass->name, (array) $route->getParams($argClass->name));
            } else {
                // 如果不是class 这直接将参数放入with中
                $with[] = $route->getParams($param->name);
            }
        }
        // invoke该方法
        $class->invokeArgs($this->app[$controller], $with);
    }

    public function all()
    {
        return $this->RequestParams;
    }

    public function __get($name)
    {
        return isset($this->RequestParams[$name]) ? $this->RequestParams[$name] : null;
    }

    public function offsetExists($offset)
    {
        return isset($offset, $this->RequestParams);
    }

    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->RequestParams[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->RequestParams[$offset]);
    }

    public function current()
    {
        return current($this->RequestParams);
    }

    public function next()
    {
        return next($this->RequestParams);
    }

    public function rewind()
    {
        return reset($this->RequestParams);
    }

    public function valid()
    {
        return !is_null(key($this->RequestParams)) ? true : false;
    }

    public function key()
    {
        return key($this->RequestParams);
    }
}
