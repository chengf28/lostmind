<?php

namespace Core\Templates;

use Core\Exception\Templates\TemplateNotFoundException;
use Core\Filesystem\Filesystem;
use Core\Templates\Compile;
use InvalidArgumentException;

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

    protected $sections      = [];

    protected $sectionsStack = [];

    /**
     * 编译模板
     * @var \Core\Templates\Compile
     * Real programmers don't read comments, novices do
     */
    protected $compile;

    public function __construct(Compile $compile)
    {
        $this->compile = $compile;
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
        extract($this->values);
        ob_start();
        $d = include $target;
        $content =  ob_get_clean();
        return $content;
    }

    public function show(string $template_name)
    {
        $content = $this->make($template_name);
        var_dump($content);
    }


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

    public function section(string $name){
        if(ob_start())
            $this->sectionsStack[] = $name;
    }

    public function sectionEnd()
    {
        if (empty($this->sectionsStack)) {
            throw new InvalidArgumentException("section has not ending tag");
        }
        $section = array_pop($this->sectionsStack);
        if (isset($this->sections[$section])) {
            return ob_get_clean() . $this->sections[$section];
        }else{
            $this->sections[$section] = ob_get_clean();
        }
    }
}
