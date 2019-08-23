<?php

namespace Core\Exception\Request;

use RuntimeException;

/**
 * Request请求方式异常
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class MethodNotAllow extends RuntimeException
{
    public function __construct($method)
    {
        parent::__construct("The $method method is not allow");
    }   
}