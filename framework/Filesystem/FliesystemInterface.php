<?php
namespace Core\Filesystem;

/**
 * 文件操作系统的接口
 * @author chengf28 <chengf_28@163.com>
 * God Bless the Code
 */
interface FilesystemInterface
{
    /**
     * 判断是否存在
     * @param string $filename
     * @return bool
     * God Bless the Code
     */
    public function has(string $filename);

    /**
     * 获取文件内容
     * @param string $filename
     * @param bool $lock
     * @return string
     * God Bless the Code
     */
    public function get(string $filename,bool $lock = false);
    
    /**
     * 向目标文件写入内容
     * @param string $filename
     * @param mixed $data
     * @return void
     * God Bless the Code
     */
    public function put(string $filename,$data,$locl=false);

    /**
     * 移动文件
     * @param string $source
     * @param string $target
     * @return bool
     * God Bless the Code
     */
    public function move(string $source, string $target);
    
    /**
     * 复制文件
     * @param string $source
     * @param string $target
     * @return bool
     * God Bless the Code
     */
    public function copy(string $source, string $target);

    /**
     * 删除文件
     * @param string $target
     * @return bool
     * God Bless the Code
     */
    public function delete(string $target);
}