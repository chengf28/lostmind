<?php

namespace Core\Router;

use Core\Router\Router;
use Core\Facade\Route;

use InvalidArgumentException;

class RouteGenerator
{
    const Regex = <<<REGEX
@
    # 匹配后面占位符
    \{
        ([A-Za-z_-]*)
    \}
@x
REGEX;

    private $collection;

    /**
     * 构建函数,依赖注入
     * @param \Core\Route\RouteCollection $collection
     * Real programmers don't read comments, novices do
     */
    public function __construct(\Core\Route\RouteCollection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * 解析
     * @param string $method
     * @param string $uri
     * @param mixed $controllerData
     * @return void
     * Real programmers don't read comments, novices do
     */
    private function parse($method, $uri, $controllerData)
    {
        list($isStatic,$router) = $this->parseUri($uri);
        $this->parseController($router, $controllerData);
        $this->collection->addRoute($method, $router,$isStatic);
    }

    /**
     * 解析控制器及方法
     * @param \Core\Router\Router|array $router
     * @param mixed $controllerData
     * @return void
     * Real programmers don't read comments, novices do
     */
    private function parseController($router, $controllerData)
    {
        if (strpos($controllerData, '@') !== false) {
            $data = explode('@', $controllerData);
            $router->controller($data[0]);
            $router->method($data[1]);
            unset($data);
        } else {
            throw new InvalidArgumentException('路由配置未指定controller或method');
        }
    }

    /**
     * 解析URI转换成正则模式
     * @param string $uri
     * @return array
     * Real programmers don't read comments, novices do
     */
    private function parseUri(string $uri)
    {
        if (!preg_match_all(self::Regex, $uri, $match, PREG_SET_ORDER |
            PREG_OFFSET_CAPTURE)) {
            return [
                true,
                (new Router)->where($uri)
            ];
        }
        $offset = 0;
        $data   = [];
        foreach ($match as $set) {
            if ($set[0][1] > $offset) {
                $data[] = substr($uri, $offset, $set[0][1] - $offset);
            }
            $data[] = [
                $set[1][0],
                '[A-Za-z_\-0-9]+'
            ];
            $offset = $set[0][1] + strlen($set[0][0]);
        }
        if ($offset !== strlen($uri)) {
            $data[] = substr($uri, $offset);
        }
        return [
            false,
            $this->generator($data)
        ];
    }

    /**
     * 添加正则模式
     * @param array $routes
     * @return \Core\Router\Router
     * Real programmers don't read comments, novices do
     */
    private function generator(array $routes)
    {
        $regex  = '^';
        $router = new Router;
        foreach ($routes as $route) {
            if (is_string($route)) {
                $regex .= $route;
                continue;
            }
            $router->addParam($route[0]);
            $regex .= '(' . $route[1] . ')';
        }
        $regex .= '+?$';
        $router->where($regex);
        return $router;
    }

    /**
     * 添加get请求路由
     * @param string $uri
     * @param mixed $controllerData
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function get(string $uri, $controllerData)
    {
        $this->parse('GET', $uri, $controllerData);
    }

    /**
     * 添加post请求路由
     * @param string $uri
     * @param mixed $controllerData
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function post(string $uri, $controllerData)
    {
        $this->parse('POST', $uri, $controllerData);
    }

    /**
     * 添加delete请求路由
     * @param string $uri
     * @param mixed $controllerData
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function delete(string $uri, $controllerData)
    {
        $this->parse('DELETE', $uri, $controllerData);
    }

    /**
     * 添加put请求路由
     * @param string $uri
     * @param mixed $controllerData
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function put(string $uri, $controllerData)
    {
        $this->parse('PUT', $uri, $controllerData);
    }
}
