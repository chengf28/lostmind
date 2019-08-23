<?php
namespace Core\Exception\Request;

use RuntimeException;

/**
 * 路由参数错误异常
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class RouteArgumentException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('The Routing Configuration Does Not Specify Controller Or Method');
    }
}
