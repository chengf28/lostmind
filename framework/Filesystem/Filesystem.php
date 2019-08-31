<?php

namespace Core\Filesystem;

use Core\Exception\Filesystem\FileNotFoundException;

/**
 * 文件操作系统
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class Filesystem implements FilesystemInterface
{
    protected $fd;
    /**
     * 构造函数
     * @param string $file
     * Real programmers don't read comments, novices do
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * 获取资源句柄
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function getFileHandle($mode = null)
    {
        if (!$this->fd) {
            $this->fd = fopen($this->file, $mode);
        }
        return $this->fd;
    }

    /**
     * 通过yeild实现同步行读写
     * @param bool $lock
     * @return \Generator
     * Real programmers don't read comments, novices do
     */
    public function line(bool $lock = false)
    {
        $this->getFileHandle('rwb+');
        $lock && flock($this->fd, LOCK_EX);
        while ($line = fgets($this->fd)) {
            // 如果写入
            $write = yield $line;
            if ($write) {
                fputs($this->fd, $write);
            }
        }
        $lock && flock($this->fd, LOCK_UN);
    }

    /**
     * 读取内容
     * @param bool $lock
     * @return string
     * Real programmers don't read comments, novices do
     */
    public function get(bool $lock = false)
    {
        $this->getFileHandle('rb+');
        $lock && flock($this->fd, LOCK_SH);
        $content = fread($this->fd, filesize($this->file));
        $lock && flock($this->fd, LOCK_UN);
        return $content;
    }

    /**
     * 写入内容
     * @param mixed $data
     * @param bool $lock
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function put($data, bool $lock = false)
    {
        $this->getFileHandle('wb+');
        $lock && flock($this->fd, LOCK_EX);
        fputs($this->fd, $data);
        $lock && flock($this->fd, LOCK_UN);
    }

    /**
     * 析构函数
     * Real programmers don't read comments, novices do
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * 关闭资源句柄
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function close()
    {
        if ($this->fd && is_resource($this->fd)) {
            fclose($this->fd);
        }
        $this->fd = null;
    }

    /**
     * 判断是否存在文件
     * @param string $filepath
     * @return bool
     * Real programmers don't read comments, novices do
     */
    public static function has(string $filepath)
    {
        return file_exists($filepath);
    }

    /**
     * 创建目录
     * @param string $dirpath
     * @param int $mode
     * @param bool $recursive
     * @return bool
     * Real programmers don't read comments, novices do
     */
    public static function dir(string $dirpath, $mode = 0777, $recursive = false)
    {
        return mkdir($dirpath, $mode, $recursive);
    }
}
