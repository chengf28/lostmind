<?php
namespace Core\Exception\Request;

use RuntimeException;

/**
 * 页面未找到异常
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class PageNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("Page is not found",404);
    }
}
