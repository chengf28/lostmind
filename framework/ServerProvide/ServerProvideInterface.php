<?php

namespace Core\ServerProvide;

/**
 * 服务器提供接口
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
interface ServerProvideInterface
{
    public function boot(\Core\Application $app);
}
