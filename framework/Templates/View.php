<?php

namespace Core\Templates;

use Core\Templates\Compile;
use Core\Filesystem\Filesystem;
use Core\Templates\ViewContent;
use Core\Exception\Templates\TemplateNotFoundException;

/**
 * View类
 * 用于模板展示
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class View
{
    protected $values;

    protected $suffix       = '.lm.php';

    protected $cache_suffix = '.php';

    protected $content;

    /**
     * 编译模板
     * @var \Core\Templates\Compile
     * Real programmers don't read comments, novices do
     */
    protected $compile;

    /**
     * ViewContent 存放模板内容
     * @var \Core\Templates\ViewContent
     * Real programmers don't read comments, novices do
     */
    protected $viewContent;

    public function __construct(Compile $compile, ViewContent $content)
    {
        $this->compile     = $compile;
        $this->viewContent = $content;
    }

    /**
     * 注入参数
     * @param array $args
     * @return \Core\Templates\Template
     * Real programmers don't read comments, novices do
     */
    public function with(array $args)
    {
        array_walk($args, function ($value, $key) {
            $this->values[$key] = $value;
        });
        return $this;
    }

    public function make(string $template_name)
    {
        list($source, $target) = $this->getComplieName($template_name);
        if (!Filesystem::has($source)) {
            throw new TemplateNotFoundException($source, $this->suffix);
        }
        /**
         * 判断编译文件是否存在
         */
        if (Filesystem::has($target)) {
            /**
             * 判断是否最后编译时与当前模板编译时间
             */
            if (filemtime($source) > filemtime($target)) {
                /**
                 * 编译
                 */
                $this->compile($source, $target);
            }
        } else {
            /**
             * 编译
             */
            $this->compile($source, $target);
        }

        if (!$this->content) {
            $this->content = $this->viewContent->import($target, $this->values);
        }
        return $this;
    }

    public function get()
    {
        return $this->content;
    }

    /**
     * 编译模板
     * @param string $source
     * @param string $traget
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function compile($source, $traget)
    {
        $this->compile->compile($source, $traget);
    }

    /**
     * 通过模板名获取到完整的源文件路径及编译后的模板名
     * @param string $filename
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function getComplieName(string $filename)
    {
        return [
            config('base.viewPath') . str_replace('.', DIRECTORY_SEPARATOR, $filename) . $this->suffix,
            config('base.templates_storage') . DIRECTORY_SEPARATOR . sha1($filename) . $this->cache_suffix
        ];
    }

}
