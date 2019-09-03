<?php

namespace Core\Templates;

use Core\Filesystem\Filesystem;
use Core\Templates\CompileTemplate;

/**
 * View类
 * 用于模板展示
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class Template
{
    protected $compile;

    protected $app;

   

    /**
     * 缓存过期时间 秒
     * @var int
     * Real programmers don't read comments, novices do
     */
    protected $cache_time = 86400;

    /**
     * 是否缓存编译模板
     * @var bool
     * Real programmers don't read comments, novices do
     */
    protected $is_cache = false;

    protected $values;

    /**
     * 构造函数
     * @param \Core\Application $app
     * @param \Core\Templates\CompileTemplate $compile
     * Real programmers don't read comments, novices do
     */
    public function __construct(CompileTemplate $compile)
    {
        $this->compile = $compile;
    }
    
    /**
     * 是否缓存
     * @param bool $isCache
     * @return bool
     * Real programmers don't read comments, novices do
     */
    public function isCache(bool $isCache)
    {
        $this->is_cache = $isCache;
    }

    /**
     * 显示模板
     * @param string $template
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function show(string $template)
    {
        $this->compile->getSource($template);
        
        $target_file_name = $template;
        $this->compile->getTarget($target_file_name);

        /**
         * 模板不存在
         * 编译模板
         */
        if (!Filesystem::has($target_file_name)) {
            $this->compile->compile($template, $target_file_name);
            if ($this->is_cache) {
                $this->complieCache($target_file_name);
            }
        }

        /**
         * 是否需要缓存
         */
        if ($this->is_cache) {
            // 文件已经到期
            if (time() - filemtime($target_file_name) > $this->cache_time) {
                // 编译模板
                $this->compile->compile($template, $target_file_name);
                $this->complieCache($target_file_name);
            }
            return readfile($target_file_name);
        } else {
            if ($this->values) {
                extract($this->values);
            }
            return include $target_file_name;
        }
    }

    /**
     * 缓存信息
     * @param string $file
     * @return void
     * Real programmers don't read comments, novices do
     */
    protected function complieCache(string $file)
    {
        ob_start();
        if ($this->values) {
            extract($this->values);
        }
        include $file;
        (new Filesystem($file))->put(ob_get_contents());
        ob_end_clean();
    }

    /**
     * 注入参数
     * @param array $args
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function with(array $args)
    {
        array_walk($args, function ($value, $key) {
            $this->values[$key] = $value;
        });
        return $this;
    }
}
