<?php

namespace Core\Router;

/**
 * 单条路由处理
 * Real programmers don't read comments, novices do
 */
class Router
{
    protected $uri;

    protected $controller;

    protected $params;

    protected $regex;

    protected $methods;

    public function __construct(string $uri = null)
    {
        if ($uri) {
            $this->setUri($uri);
        }
    }

    /**
     * 设置uri
     * @param string $uri
     * @return \Core\Router\Router
     * Real programmers don't read comments, novices do
     */
    public function setUri(string $uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * 获得uri
     * @return string
     * Real programmers don't read comments, novices do
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * 添加正则匹配
     * @param string $params
     * @param string $regex
     * @return \Core\Router\Router
     * Real programmers don't read comments, novices do
     */
    public function where(string $params, string $regex)
    {
        $this->regex[$params] = $regex;
        return $this;
    }

    /**
     * 获得正则匹配
     * @return string
     * Real programmers don't read comments, novices do
     */
    public function getRegex()
    {
        return str_replace(array_keys($this->regex), array_values($this->regex), $this->uri);
    }

    /**
     * 设置参数
     * @param array $param
     * @return \Core\Router\Router
     * Real programmers don't read comments, novices do
     */
    public function setParam(array $param)
    {
        $this->params[] = $param['name'];
        $this->regex[$param['name']] = $param['regex'];
        return $this;
    }

    /**
     * 设置参数对应的值
     * @param array $value
     * @return \Core\Router\Router
     * Real programmers don't read comments, novices do
     */
    public function setParamValue(array $value)
    {
        $this->params = array_combine($this->params, $value);
        return $this;
    }

    /**
     * 获取到参数信息
     * @param string|int $key
     * @return array|string
     * Real programmers don't read comments, novices do
     */
    public function getParams($key = null)
    {
        if ($key) {
            return isset($this->params[$key]) ? $this->params[$key] : null;
        }
        return $this->params ?: [];
    }

    /**
     * 设置控制器方法名
     * @param string $controller
     * @return string
     * Real programmers don't read comments, novices do
     */
    public function controller(string $controller = null)
    {
        if ($controller) {
            $this->controller = $controller;
        }
        return $this->controller;
    }
}
