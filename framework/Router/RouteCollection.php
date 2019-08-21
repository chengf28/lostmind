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
            $this->static[$method][$routeData->getUri()] = $routeData;
        } else {
            $this->routers[$method][] = $routeData;
        }
    }

    /**
     * 匹配路由
     * @param string $method
     * @param string $uri
     * @return \Core\Router\Router
     * Real programmers don't read comments, novices do
     */
    public function match(string $method, string $uri)
    {
        if(!isset($this->routers[$method]))
        {
            return null;
        }
        foreach ($this->routers[$method] as $router) {
            if (!preg_match_all('~' . $router->getRegex() . '~x', $uri, $match)) {
                continue;
            }
            unset($match[0]);
            $router->setParamValue(array_column($match, '0'));
            return $router;
        }
    }

    /**
     * 匹配静态路由参数
     * @param string $method
     * @param string $uri
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function staticMatch(string $method, string $uri)
    {
        return isset($this->static[$method][$uri]) ? $this->static[$method][$uri] : null;
    }
}
