<?php

namespace Core\ServerProvide;

use Core\ServerProvide\ServerProvideAbstract;

/**
 * 路由注册服务
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
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
