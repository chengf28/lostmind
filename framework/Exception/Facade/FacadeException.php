<?php

namespace Core\Exception\Facade;

use RuntimeException;

/**
 * facade模式下未能找到相关类异常
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class FacadeException extends RuntimeException
{
    public function __construct($name)
    {
        parent::__construct("The Class {$name} Is Not Exists");
    }
}
