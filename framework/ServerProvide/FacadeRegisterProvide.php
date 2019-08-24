<?php

namespace Core\ServerProvide;

use Core\Facade\Facade;

/***
 * 门面模式注册服务器
 * @author chengf28 <chengf_28@163.com>
 */
class FacadeRegisterProvide extends ServerProvideAbstract
{
    /**
     * 抽象接口
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function start()
    {
        // 注册Application到Facade中
        Facade::setFacadeApplication($this->app);
    }
}
