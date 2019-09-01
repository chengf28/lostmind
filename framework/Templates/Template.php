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
    protected $suffix       = '.lm.php';

    /**
     * 编译后文件后缀名
     * @var string
     * Real programmers don't read comments, novices do
     */
    protected $cache_suffix = '.html';

    /**
     * 缓存过期时间 秒
     * @var int
     * Real programmers don't read comments, novices do
     */
    protected $cache_time = 86400;

    /**
     * 构造函数
     * @param \Core\Application $app
     * @param \Core\Templates\CompileTemplate $compile
     * Real programmers don't read comments, novices do
     */
    public function __construct(Application $app, CompileTemplate $compile)
    {
        $this->compile = $compile;
        $this->app     = $app;
    }

    /**
     * 显示模板
     * @param string $template
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function show(string $template)
    {
        $source_file_name = $this->app->make('app.viewPath') . str_replace('.', DIRECTORY_SEPARATOR, $template) . $this->suffix;
        // 检查是否存在文件
        if (!Filesystem::has($source_file_name)) {
            throw new TemplateNotFoundException("Not found the $template");
        }
        $target_file_name = $this->app->getConfig('base.templates_storage') . DIRECTORY_SEPARATOR . sha1_file($source_file_name) . $this->cache_suffix;

        /**
         * 检查模板是否存在
         */
        if (!Filesystem::has($target_file_name)) {
            /**
             * 编译模板
             */
            $this->compile->compile($source_file_name, $target_file_name);
        } else {
            /**
             * 检查缓存是否到期
             */
            if (time() - filectime($target_file_name) > $this->cache_time) {
                /**
                 * 编译模板
                 */
                $this->compile->compile($source_file_name, $target_file_name);
            }
        }
        readfile($target_file_name);
    }
}
