<?php

namespace Core\Router;

use Core\Router\Router;
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


    public function __construct(\Core\Route\RouteCollection $collection)
    {
        $this->collection = $collection;
    }

    public function parse(string $uri)
    {
        if (!preg_match_all(self::Regex, $uri, $match, PREG_SET_ORDER |
            PREG_OFFSET_CAPTURE)) {
            return [
                'regex' => $uri
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
        return $this->generator($data);
    }

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

    public function get(string $uri, $controllerData)
    {
        $router = $this->parse($uri);
        $this->parseController($router,$controllerData);
        $this->collection->addRoute('GET', $router);
    }

    public function post(string $uri, $controllerData)
    {
        $router = $this->parse($uri);
        $this->parseController($router, $controllerData);
        $this->collection->addRoute('POST', $router);
    }

    public function delete(string $uri, $controllerData)
    {
        $router = $this->parse($uri);
        $this->parseController($router, $controllerData);
        $this->collection->addRoute('DELETE', $router);
    }

    public function put(string $uri, $controllerData)
    {
        $router = $this->parse($uri);
        $this->parseController($router, $controllerData);
        $this->collection->addRoute('PUT', $router);
    }

    private function parseController(Router $router,$controllerData)
    {
        if (strpos($controllerData, '@') !== false) {
            $data = explode('@', $controllerData);
            $router->controller($data[0]);
            $router->method($data[1]);
            unset($data);
        }else{
            throw new InvalidArgumentException('路由配置未指定controller或method');
        }
    }

    public function loader(string $path)
    {
        include $path;
    }
}
