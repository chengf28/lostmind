<?php

namespace Core\Router;

/**
 * 路由构建类
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class RouteGenerator
{
    const Regex = <<<REGEX
~
    # 匹配后面占位符
    \{
        ([A-Za-z_-]*)
    \}
~x
REGEX;

    /**
     * router集合
     * @var \Core\Route\RouteCollection
     * Real programmers don't read comments, novices do
     */
    protected $collection;

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
     * @return \Core\Router\Router
     * Real programmers don't read comments, novices do
     */
    protected function parse($method, $uri, $controllerData)
    {
        $uri      = '/' . ltrim($uri, '/');
        list($has,$router) = $this->collection->getRouter($uri);
        if(!$has)
        {
            $isStatic = $this->parseUri($router, $uri);
            $this->collection->addRouter($uri, $isStatic, $router);
        }
        $router->controller($method, $controllerData);
        return $router;
    }

    /**
     * 解析URI转换成正则模式
     * @param string $uri
     * @return bool
     * Real programmers don't read comments, novices do
     */
    protected function parseUri(Router $router, string $uri)
    {
        if (!preg_match_all(self::Regex, $uri, $match, PREG_SET_ORDER |
            PREG_OFFSET_CAPTURE)) {
            return true;
        }
        $offset = 0;
        $regex  = '';
        foreach ($match as $set) {
            if ($set[0][1] > $offset) {
                $regex .= substr($uri, $offset, $set[0][1] - $offset);
            }
            $router->setParam([
                'name' => $set[1][0],
                'regex' => '[A-Za-z_\-0-9]+'
            ]);
            $regex .= '(:' . $set[1][0] . ')';
            $offset = $set[0][1] + strlen($set[0][0]);
        }
        $regex .= '+?$';
        $router->setRegex($regex);
        return false;
    }

    /**
     * 添加get请求路由
     * @param string $uri
     * @param mixed $controllerData
     * @return \Core\Router\Router
     * Real programmers don't read comments, novices do
     */
    public function get(string $uri, $controllerData)
    {
        return $this->parse('GET', $uri, $controllerData);
    }

    /**
     * 添加post请求路由
     * @param string $uri
     * @param mixed $controllerData
     * @return \Core\Router\Router
     * Real programmers don't read comments, novices do
     */
    public function post(string $uri, $controllerData)
    {
        return $this->parse('POST', $uri, $controllerData);
    }

    /**
     * 添加delete请求路由
     * @param string $uri
     * @param mixed $controllerData
     * @return \Core\Router\Router
     * Real programmers don't read comments, novices do
     */
    public function delete(string $uri, $controllerData)
    {
        return $this->parse('DELETE', $uri, $controllerData);
    }

    /**
     * 添加put请求路由
     * @param string $uri
     * @param mixed $controllerData
     * @return \Core\Router\Router
     * Real programmers don't read comments, novices do
     */
    public function put(string $uri, $controllerData)
    {
        return $this->parse('PUT', $uri, $controllerData);
    }
}
