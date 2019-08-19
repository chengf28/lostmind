<?php

namespace Core\Route;

use Core\Router\Router;

class RouteCollection
{
    private $routers = [];

    private $static = [];
    /**
     * 添加合集
     * @param string $method
     * @param \Core\Router\Router $routeData
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function addRoute(string $method, Router $routeData, bool $isStatic)
    {
        $this->{$isStatic ? 'static' : 'routers'}[$method][] = $routeData;
    }


    public function match(string $method, string $uri)
    {
        foreach ($this->routers[$method] as $router) {
            var_dump($router);
        }
    }
}
