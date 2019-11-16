<?php

namespace Core\Templates;

class ViewContent
{
    protected $sectionsStack;

    protected $sections;

    protected $deep = 0;

    public function import(string $fileName,$value)
    {
        extract($value);
        ob_start();
        $level = ob_get_level();
        include $fileName;
        $ret = ob_get_clean();
        return $ret;
    }

    public function section(string $name)
    {
        if (ob_start())
        {
            ++$this->deep;
            $this->sectionsStack[] = $name;
        }
    }

    public function endSection()
    {
        /**
         * 判断堆栈中是否还有没有处理的Section
         */
        if (empty($this->sectionsStack)) {
            throw new InvalidArgumentException("Section has not ending tag");
        }
        
        /**
         * 将section块之间的内容堆入栈中
         */
        if ($this->deep > 0) {
            --$this->deep;
            $section = array_pop($this->sectionsStack);
            $this->sections[$section] = ob_get_clean();
        }
    }

    public function showSection()
    {
        /**
         * 判断堆栈中是否还有没有处理的Section
         */
        if (empty($this->sectionsStack)) {
            throw new InvalidArgumentException("Section has not ending tag");
        }
        if ($this->deep > 0) {
            $a = ob_get_clean();
            --$this->deep;
            $section = array_pop($this->sectionsStack);
            if (isset($this->sections[$section])) {
                echo $this->sections[$section];
            }
        }

    }

    /**
     * 寻找继承
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function show($templename)
    {
        \Core\Facade\View::make($templename);
    }
}
