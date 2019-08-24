<?php

namespace Core\Facade;

use Core\Exception\Facade\FacadeException;


/**
 * 外观模式基类
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
abstract class Facade
{
    /**
     * 存放已经实例化好的类
     * @var array
     * Real programmers don't read comments, novices do
     */
    protected static $instances;

    /**
     * 存放App容器
     * @var \Core\Application
     * Real programmers don't read comments, novices do
     */
    protected static $app;

    /**
     * 获取到obj名字
     * @return string
     * Real programmers don't read comments, novices do
     */
    abstract protected static function getFacadeName();

    /**
     * 设置App容器
     * @param \Core\Application $app
     * @return void
     * Real programmers don't read comments, novices do
     */
    public static function setFacadeApplication(\Core\Application $app)
    {
        self::$app = $app;
    }

    /**
     * 调用客户端
     * @return object
     * Real programmers don't read comments, novices do
     */
    protected static function getFacadeObject()
    {
        $obj_name = static::getFacadeName();
        if (isset(static::$instances[$obj_name])) {
            return static::$instances[$obj_name];
        }
        if (!is_null(static::$app) && static::$app instanceof \Core\Application) {
            return static::$instances[$obj_name] = static::$app[$obj_name];
        }
        throw new FacadeException($obj_name);
    }

    /**
     * 调用
     * @param string $method_name
     * @param array $arguments
     * @return object
     * Real programmers don't read comments, novices do
     */
    public static function __callStatic($method_name, $arguments)
    {
        return (static::getFacadeObject())->{$method_name}(...$arguments);
    }
}
