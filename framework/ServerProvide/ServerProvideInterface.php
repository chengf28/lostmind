<?php

namespace Core\ServerProvide;

/**
 * 服务器提供接口
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
interface ServerProvideInterface
{
    /**
     * 启动服务器
     * @param \Core\Application $app
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function boot(\Core\Application $app);
}
