<?php
namespace Core\Router;

class Route
{
    public function get()
    {

    }
    
    public function loader(string $path)
    {
        require $path;
    }   
}