<?php
namespace Core\ServerProvide;
use Core\ServerProvide\ServerProvideInterface as SPInterface;

abstract class ServerProvideAbstract implements SPInterface
{
    public $app;

    public function boot(\Core\Application $app)
    {
        $this->app = $app;
        $this->start();
    }
    
    abstract public function start();
}