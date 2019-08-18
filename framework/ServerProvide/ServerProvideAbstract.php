<?php
namespace Core\ServerProvide;
use Core\ServerProvide\ServerProvideInterface as SPInterface;

abstract class ServerProvideAbstract implements SPInterface
{
    /**
     * app容器
     * @var \Core\Application
     * Real programmers don't read comments, novices do
     */
    public $app;

    public function boot(\Core\Application $app)
    {
        $this->app = $app;
        $this->start();
    }
    
    abstract public function start();
}