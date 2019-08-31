<?php

namespace Core\Filesystem;

/**
 * 文件操作系统的接口
 * @author chengf28 <chengf_28@163.com>
 * If I can go death, I will
 */
interface FilesystemInterface
{
    /**
     * 构造函数
     * @param string $file
     * Real programmers don't read comments, novices do
     */
    public function __construct($file);

    /**
     * 获取资源句柄
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function getFileHandle();

    /**
     * 通过yeild实现同步行读写
     * @param bool $lock
     * @return \Generator
     * Real programmers don't read comments, novices do
     */
    public function line(bool $lock = false);
    
    /**
     * 读取内容
     * @param bool $lock
     * @return string
     * Real programmers don't read comments, novices do
     */
    public function get(bool $lock = false);

    /**
     * 写入内容
     * @param mixed $data
     * @param bool $lock
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function put($data, bool $lock = false);
    
    /**
     * 析构函数
     * Real programmers don't read comments, novices do
     */
    public function __destruct();
    
    /**
     * 关闭资源句柄
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function close();

    /**
     * 判断是否存在文件
     * @param string $filepath
     * @return bool
     * Real programmers don't read comments, novices do
     */
    public static function has(string $filepath);
    
    /**
     * 创建目录
     * @param string $dirpath
     * @param int $mode
     * @param bool $recursive
     * @return bool
     * Real programmers don't read comments, novices do
     */
    public static function dir(string $dirpath, $mode = 0777, $recursive = false);
}
