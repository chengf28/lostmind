<?php
namespace Core\Templates;

use Core\Filesystem\Filesystem;

/**
 * 模板编译
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class CompileTemplate
{
    public function compile($source,$targe)
    {
        $file       = new Filesystem($source);
        $targe_file = new Filesystem($targe);
        foreach ( $file->line() as $value) {
            // 标签解析TODO
            $targe_file->put($value);
        }
        unset($file,$targe_file);
    }
}