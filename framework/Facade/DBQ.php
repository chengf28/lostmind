<?php

namespace Core\Facade;

use Core\Facade\Facade;

/**
 * DBquery外观模式
 * @author chengf28 <chengf_28@163.com>
 * @see \Core\Router\RouteGenerator
 * Real programmers don't read comments, novices do
 */
class DBQ extends Facade
{
    protected static function getFacadeName()
    {
        return 'DBQ';
    }
    
}
