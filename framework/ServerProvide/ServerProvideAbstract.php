<?php

namespace Core\ServerProvide;

use Core\ServerProvide\ServerProvideInterface as SPInterface;

/**
 * 服务提供基类
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
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
