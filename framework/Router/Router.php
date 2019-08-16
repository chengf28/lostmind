<?php

namespace Core\Router;

class Router
{ 
    
    private $collection;

    private $generator;

    public function __construct(\Core\Route\RouteCollection $collection, \Core\Router\RouteGenerator $generator)
    {
        $this->collection = $collection;
        $this->generator  = $generator;
    }

    public function get()
    {

    }
}
