<?php

namespace Core\Filesystem;

use Core\Exception\Filesystem\FileExistsException;
use Core\Exception\Filesystem\FileNotFoundException;


/**
 * 文件操作系统
 * @author chengf28 <chengf_28@163.com>
 * IF I CAN GO DEATH, I WILL
 */
class Filesystem implements FilesystemInterface
{
    protected $fd;

    protected $lock;
    /**
     * 判断是否存在
     * @param string $filename
     * @return bool
     * IF I CAN GO DEATH, I WILL
     */
    public static function has(string $filename)
    {
        return file_exists($filename);
    }

    /**
     * 读取数据
     * @param string $filename
     * @param bool $lock
     * @return string
     * IF I CAN GO DEATH, I WILL
     */
    public function get(string $filename, bool $lock = false)
    {
        $content = '';
        if (!is_readable($filename)) {
            throw new FileNotFoundException($filename, 1);
        }
        if (!is_file($filename)) {
            throw new \InvalidArgumentException("Is not a File at path $filename", 2);
        }
        // 打开资源句柄
        $fd = fopen($filename, 'rb+');
        if (!$fd) {
            throw new \LogicException("Can't not create file stream", 4);
        }
        // 加入锁
        $lock && flock($fd, LOCK_SH);
        // 读取内容
        $content = fread($fd, filesize($filename));
        // 解锁
        $lock && flock($fd, LOCK_UN);
        // 关闭资源句柄
        fclose($fd);
        return $content;
    }

    /**
     * 按行读取文件内容
     * @param string $filename
     * @param bool $lock
     * @return \Generator
     * IF I CAN GO DEATH, I WILL
     */
    public function getLine(string $filename, bool $lock = false)
    {
        $this->lock = $lock;
        if (!is_readable($filename)) {
            throw new FileNotFoundException($filename, 1);
        }
        if (!is_file($filename)) {
            throw new \InvalidArgumentException("Is not a File at path $filename", 2);
        }
        // 打开资源句柄
        $this->fd = $fd = fopen($filename, 'rb+');
        if (!$fd) {
            throw new \LogicException("Can't not create file stream", 4);
        }
        // 加入锁
        $this->lock && flock($fd, LOCK_SH);
        while ($line = fgets($fd)) {
            yield rtrim($line);
        }
    }
    /**
     * 插入数据
     * @param string $filename
     * @param mixed $data
     * @param bool $lock
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    public function put(string $filename, $data, $lock = false)
    {
        if ($this->has($filename)) {
            if (!is_writeable($filename)) {
                throw new \LogicException("Can't not wirte file at paht $filename", 1);
            }
            if (!is_file($filename)) {
                throw new \InvalidArgumentException("Is not a File at path $filename", 2);
            }
        }
        if (is_array($data)) {
            $data = implode(PHP_EOL, $data);
        }
        $fd = fopen($filename, 'ab+');
        if (!$fd) {
            throw new \LogicException("Can't not create file stream", 4);
        }
        $lock && flock($fd, LOCK_EX);
        fputs($fd, $data);
        $lock && flock($fd, LOCK_UN);
        fclose($fd);
    }

    /**
     * 检查源及目标
     * @param string $source
     * @param string $target
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    private static function checkST(string $source, string $target)
    {
        // 源不存在
        if (!$this->has($source)) {
            throw new FileNotFoundException($source, 5);
        }
        // 目标已经存在
        if ($this->has($target)) {
            throw new FileExistsException($target, 6);
        }
    }

    /**
     * 移动文件
     * @param string $source
     * @param string $target
     * @return bool
     * IF I CAN GO DEATH, I WILL
     */
    public static function move(string $source, string $target)
    {
        self::checkST($source, $target);
        return rename($source, $target);
    }

    /**
     * 复制文件
     * @param string $source
     * @param string $target
     * @return bool
     * IF I CAN GO DEATH, I WILL
     */
    public static function copy(string $source, string $target)
    {
        // 检查
        self::checkST($source, $target);
        return copy($source, $target);
    }

    /**
     * 删除文件
     * @param string $target
     * @return bool
     * IF I CAN GO DEATH, I WILL
     */
    public static function delete(string $target)
    {
        return unlink($target);
    }

    /**
     * 关闭资源句柄
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function close()
    {
        /**
         * 解锁
         */
        $this->lock && flock($this->fd, LOCK_EX);
        if ($this->fd) {
            // 关闭句柄
            fclose($this->fd);
        }
    }
}
