<?php

namespace Core\Router;


class Router
{
    private $uri;
    
    private $controller;

    private $method;

    private $params;

    public function __construct(string $uri = null)
    {
        if ($uri) 
        {
            $this->setUri($uri);
        }
    }

    public function setUri(string $uri)
    {
        $this->uri = $uri;
        return $this;
    }

    public function where(string $params, string $regex)
    {
        // $this->uri = str_replace($params,$regex,$this->uri);
        return $this;
    }

    public function getRegex()
    {
        var_dump(
            array_column($this->params,'name')
        );
        // var_dump($this->uri);die;
        
        // $this->uri = str_replace(['(',')'],['{','}'],$this->uri);
        // var_dump($this->uri);
        die;
    }

    public function setParam(array $param)
    {
        $this->params[]= $param;
    }

    public function controller(string $controller = null)
    {
        if ($controller) 
        {
            $this->controller = $controller;
        }
        return $this->controller;
    }

    public function method(string $method = null)
    {
        if ($method) {
            $this->method = $method;
        }
        return $this->method;
    }
}
