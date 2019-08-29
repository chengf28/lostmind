<?php

namespace Core\Templates;

use Core\Application;
use Core\Exception\Filesystem\TemplateNotFoundException;
use Core\Filesystem\Filesystem;
use Core\Templates\CompileTemplate;

/**
 * 模板文件
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class Template
{
    protected $compile;

    protected $app;

    /**
     * 模板源文件后缀名
     * @var string
     * Real programmers don't read comments, novices do
     */
    protected $suffix = '.lm.php';

    protected $cache_suffix = '.lm.php';

    public function __construct(Application $app, CompileTemplate $compile)
    {
        $this->compile = $compile;
        $this->app     = $app;
    }

    public function show($template)
    {
        $source_file_name = str_replace('.', DIRECTORY_SEPARATOR, $template);

        // 检查是否存在文件
        if (!Filesystem::has(
            $this->app->make('app.viewPath') . $source_file_name . $this->suffix
        )) { 
            throw new TemplateNotFoundException("Not found the $template");
        }
        $file_name = sha1($source_file_name);

        // 检查模板是否存在
    }
}
