<?php

namespace Core\Templates;

use Core\Templates\Compile;

/**
 * View类
 * 用于模板展示
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class View
{
    protected $values;

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
        array_walk($args,function($value,$key){
            $this->values[$key] = $value;
        });
        return $this;
    }

    
    public function show(string $template_name)
    {
        // $this->compile->check($template_name);
        $this->compile->compile($template_name);
    }
}
