<?php
namespace Core\Exception\Filesystem;
/**
 * 文件已经存在异常类
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class FileExistsException extends \Exception
{
    public function __construct($path,$code,$previous = null)
    {
        parent::__construct("File already exits at path $path",$code,$previous);
    }
}