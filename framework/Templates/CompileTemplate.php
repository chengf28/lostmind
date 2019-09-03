<?php

namespace Core\Templates;

use Core\Application;
use Core\Filesystem\Filesystem;
use Core\Exception\Filesystem\TemplateNotFoundException;

/**
 * 模板编译类
 * 编译模板用途
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class CompileTemplate
{
    
    protected $methodName = [
        'extends'
    ];

    protected $app;

    /**
     * 模板源文件后缀名
     * @var string
     * Real programmers don't read comments, novices do
     */
    protected $suffix       = '.lm.php';

    /**
     * 编译后文件后缀名
     * @var string
     * Real programmers don't read comments, novices do
     */
    protected $cache_suffix = '.php';

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 编译文件
     * @param 源文件 $source
     * @param 目标文件 $targe
     * @return void|array
     * Real programmers don't read comments, novices do
     */
    public function compile($source, $targe)
    {
        $file       = new Filesystem($source);
        $targe_file = new Filesystem($targe);
        $content = $targe_file->arrayPut(true);
        foreach ($file->line() as $value) {
            $this->parse($value);
            // var_dump($value);
            $content->send($value);
        }

        // 关闭资源句柄
        unset($file, $targe_file,$content);
    }

    protected function extends($source)
    {
        $this->getSource($source);
        $source = new Filesystem($source);
        $ret_content = '';
        foreach ($source->line() as $content) {
            $ret_content .= $content;
        }
        return $ret_content;
    }

    public function getSource(&$filename)
    {
        $filename = $this->app->make('app.viewPath') . str_replace('.', DIRECTORY_SEPARATOR, $filename) . $this->suffix;
        // 检查是否存在文件
        if (!Filesystem::has($filename)) {
            throw new TemplateNotFoundException("Not found the $filename");
        }
        unset($filename);
    }

    public function getTarget(&$filename)
    {
        $filename = $this->app->getConfig('base.templates_storage') . DIRECTORY_SEPARATOR . sha1_file($filename) . $this->cache_suffix;
        unset($filename);
    }

    public function parse(&$value)
    {
        if (strpos($value, '@') !== false) {
            if (preg_match(
                '~\@([A-Za-z]+)\(([A-Za-z.]+)\)~x',
                $value,
                $res
            )) {
                if (in_array($res[1], $this->methodName)) {
                    $value = $this->{$res[1]}($res[2]);
                }
            }
        }
        // 标签解析TODO
        if (strpos($value, '{{') !== false) {
            // 匹配变量
            $value = preg_replace(
                [
                    '~\{\{(\$[A-Za-z_\-\x7f-\xff][A-Za-z_\-\x7f-\xff0-9]+?)\}\}~x',
                    '~\{\{([A-Za-z\x7f-\xff][A-Za-z0-9_\-\x7f-\xff]*)\(([\'\"A-Za-z0-9_\-]*)\)\}\}~x'
                ],
                ['<?php echo $1;?>','<?php echo $1($2) ?>'],
                $value
            );
        }
    }
}
