<?php
namespace Core\Exception\Filesystem;

use RuntimeException;

/**
 * 文件不存在异常
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class FileNotFoundException extends RuntimeException
{
    public function __construct($path)
    {
        parent::__construct("$path is not found");
    }
}