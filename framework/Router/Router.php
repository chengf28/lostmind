<?php

namespace Core\Router;

class Router
{
    private $regex;

    private $params;

    private $controller;

    private $method;

    /**
     * 添加正则公式
     * @param string $regex
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function where(string $regex)
    {
        $this->regex = $regex;
    }

    /**
     * 获得正则
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function getRegex()
    {
        return $this->regex;
    }

    /**
     * 添加参数
     * @param mixed $param
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function addParam($param)
    {
        $this->params[] = $param;
    }

    /**
     * 添加控制器
     * @param string $controller
     * @return string
     * Real programmers don't read comments, novices do
     */
    public function controller(string $controller = null)
    {
        if (!is_null($controller)) {
            $this->controller = $controller;
        }
        return $this->controller;
    }

    /**
     * 方法
     * @param string $method
     * @return string
     * Real programmers don't read comments, novices do
     */
    public function method(string $method = null)
    {
        if (!is_null($method)) {
            $this->method = $method;
        }
        return $this->method;
    }
}
