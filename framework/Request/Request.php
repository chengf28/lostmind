<?php
namespace Core\Request;

use Core\Route\RouteCollection;

class Request
{
    protected $method;

    protected $head;

    public function __construct(RouteCollection $routeCollection)
    {
        var_dump($routeCollection);
    }

    public function send()
    {

    }
}
