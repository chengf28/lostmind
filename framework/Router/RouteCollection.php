<?php

namespace Core\Route;

use Core\Exception\Request\MethodNotAllow;
use Core\Router\Router;

/**
 * 路由集合类
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class RouteCollection
{
    private $routers = [];

    private $static = [];
    /**
     * 添加合集
     * @param \Core\Router\Router $routeData
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function addRoute(Router $routeData, bool $isStatic, string $method)
    {
        if ($isStatic) {
            if (isset($this->static[$routeData->getUri()])) {
                $static = $this->static[$routeData->getUri()];
            }
            $this->static[$routeData->getUri()] = $routeData;
        } else {
            $this->routers[] = $routeData;
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

        foreach ($this->routers as $data) {
            $router = $data[0];
            if (!preg_match_all('~' . $router->getRegex() . '~x', $uri, $match)) {
                continue;
            }
            if (!in_array($method, $data[1])) {
                throw new MethodNotAllow($method);
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
        if (!isset($this->static[$uri])) {
            return null;
        }
        $data = $this->static[$uri];
        if (!in_array($method, $data[1])) {
            throw new MethodNotAllow($method);
        }
        return $data[0];
        // return isset($this->static[$method][$uri]) ? $this->static[$method][$uri] : null;
    }
}
