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
        if ($isStatic) {
            $this->static[$method][$routeData->getRegex()] = $routeData;
        } else {
            $this->routers[$method][] = $routeData;
        }
        // $this->{$isStatic ? 'static' : 'routers'}[$method][] = $routeData;
    }


    public function match(string $method, string $uri)
    {
        foreach ($this->routers[$method] as $router) {
            if(!preg_match_all($router->getRegex(),$uri,$res))
            {
                continue;
            }
            var_dump($res,$router);
        }
    }

    public function staticMatch(string $method, string $uri)
    {
        return isset($this->static[$method][$uri]) ? $this->static[$method][$uri] : null;
    }
}
