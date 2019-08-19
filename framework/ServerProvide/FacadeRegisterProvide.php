<?php
namespace Core\ServerProvide;

use Core\Facade\Facade;

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
