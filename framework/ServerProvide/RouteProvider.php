<?php

namespace Core\ServerProvide;

use Core\Router\RouteGenerator;
use Core\ServerProvide\ServerProvideAbstract;

class RouteProvider extends ServerProvideAbstract
{
    public function start()
    {
        $this->loader();
    }

    /**
     * 路由模块加载
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function loader()
    {
        $this->app->singleBind(RouteGenerator::class);
    }
}
