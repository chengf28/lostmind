<?php

namespace Core\Facade;

use Core\Facade\Facade;

/**
 * 路由外观模式
 * @author chengf28 <chengf_28@163.com>
 * @see \Core\Router\RouteGenerator
 * Real programmers don't read comments, novices do
 */
class Route extends Facade
{
    protected static function getFacadeName()
    {
        return 'route';
    }
}
