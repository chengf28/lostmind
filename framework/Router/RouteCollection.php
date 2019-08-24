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

    public function __construct()
    {
        /**
         * 初始化集合
         */
        $this->routers = [
            'static' => [],
            'dynami' => [],
        ];
    }

    /**
     * 判断是否已经存在路由
     * @param string $uri
     * @param bool $isStatic
     * @return bool
     * Real programmers don't read comments, novices do
     */
    public function getRouter(string $uri)
    {
        if (isset($this->routers['static'][$uri])) {
            return [true, $this->routers['static'][$uri]];
        }
        if (isset($this->routers['dynami'][$uri])) {
            return [true, $this->routers['dynami'][$uri]];
        }
        return [false, new Router];
    }
    /**
     * 添加合集
     * @param \Core\Router\Router $routeData
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function addRouter(string $uri, bool $isStatic, Router $router)
    {
        $this->routers[$isStatic ? 'static' : 'dynami'][$uri] = $router;
        return $this;
    }

    /**
     * 匹配路由
     * @param string $uri
     * @return \Core\Router\Router
     * Real programmers don't read comments, novices do
     */
    public function match(string $uri)
    {
        foreach ($this->routers['dynami'] as $router) {
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
     * @param string $uri
     * @return \Core\Router\Router
     * Real programmers don't read comments, novices do
     */
    public function staticMatch(string $uri)
    {
        if (!isset($this->routers['static'][$uri])) {
            return null;
        }
        return $this->routers['static'][$uri];
    }
}
