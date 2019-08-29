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
     * 判断是否存在
     * @param string $filename
     * @return bool
     * If I can go death, I will
     */
    public static function has(string $filename);

    /**
     * 获取文件内容
     * @param string $filename
     * @param bool $lock
     * @return string
     * If I can go death, I will
     */
    public function get(string $filename, bool $lock = false);

    /**
     * 按行读取文件内容
     * @param string $filename
     * @param bool $lock
     * @return \Generator
     * IF I CAN GO DEATH, I WILL
     */
    public function getLine(string $filename, bool $lock = false);
    /**
     * 向目标文件写入内容
     * @param string $filename
     * @param mixed $data
     * @return void
     * If I can go death, I will
     */
    public function put(string $filename, $data, $locl = false);

    /**
     * 移动文件
     * @param string $source
     * @param string $target
     * @return bool
     * If I can go death, I will
     */
    public static function move(string $source, string $target);

    /**
     * 复制文件
     * @param string $source
     * @param string $target
     * @return bool
     * If I can go death, I will
     */
    public static function copy(string $source, string $target);

    /**
     * 删除文件
     * @param string $target
     * @return bool
     * If I can go death, I will
     */
    public static function delete(string $target);

    /**
     * 关闭资源句柄
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function close();
}
