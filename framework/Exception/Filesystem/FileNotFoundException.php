<?php
namespace Core\Exception\Filesystem;

/**
 * 文件不存在异常
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class FileNotFoundException extends \Exception
{
    public function __construct($path,$code,$previous = null)
    {
        parent::__construct('File not found at path:'.$path,$code,$previous);
    }
}