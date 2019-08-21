<?php

namespace Core\ServerProvide;

use Core\Facade\Route;
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
    protected function loader()
    {
        require __ROOT__ . 'routes/web.php';
    }

}
