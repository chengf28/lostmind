<?php
namespace Core\Request;

use Core\Route\RouteCollection;

class Request
{
    protected $method;

    protected $uri;

    protected $routes;

    public function __construct(RouteCollection $routeCollection)
    {
        $this->routes = $routeCollection;
        $this->uri    = trim($_SERVER['REQUEST_URI'],'/');
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    public function send()
    {
        $route = $this->routes->staticMatch($this->method,$this->uri);
        if (!$route) 
        {
            $this->routes->match($this->method,$this->uri);
        }
    }
}
