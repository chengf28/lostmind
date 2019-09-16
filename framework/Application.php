<?php

namespace Core;

use Core\Container\Container;
use Core\Exception\Filesystem\FileNotFoundException;

/**
 * 框架核心
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class Application extends Container
{
    protected $rootPath;

    protected $EnvironmentFileName = '.env';

    protected $config  = [];
    /**
     * 构造
     * @param string $path
     * IF I CAN GO DEATH, I WILL
     */
    public function __construct(string $path)
    {
        $this->rootPath = trim($path, '\\') . DIRECTORY_SEPARATOR;
        // 存入实例
        $this->bind('app', Application::class);
        $this->instances(Application::class, $this);
        // 错误与异常处理注册
        $this->instances('ExcepthonHandler', new \Core\Exception\ExceptionHandler($this));
        $this->instances('app.rootPath', $this->rootPath);
        $this->instances('app.envPath', $this->rootPath);
        $this->instances('app.configPath', $this->rootPath . 'config' . DIRECTORY_SEPARATOR);
        $this->setConfig('base.viewPath', $this->rootPath . 'views' . DIRECTORY_SEPARATOR);
    }

    /**
     * 开始
     * @return \Core\Request\Request
     * IF I CAN GO DEATH, I WILL
     */
    public function start()
    {
        // 框架内容注册
        $this->baseRegister();

        // 加载服务
        $this->loadServerProvides();

        return $this->make('Request');
    }



    /**
     * 基础服务注册
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    protected function baseRegister()
    {
        // 全局单例
        foreach ([
            \Core\Route\RouteCollection::class => \Core\Route\RouteCollection::class,
            // 请求
            'Request' => \Core\Request\Request::class,
            // 数据库管理
            'DBquery' => \Core\Database\DBmanage::class,
            // 路由构建
            'route' => \Core\Router\RouteGenerator::class,
        ]
            as $abstract => $concrete) {
            $this->singleBind($abstract, $concrete);
        }


        /**
         * 绑定
         */
        foreach ([
            'view' => \Core\Templates\View::class,
        ] as $abstract => $concrete) {
            $this->bind($abstract, $concrete);
        }
    }

    /**
     * 加载初始化工具
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    protected function loadServerProvides()
    {
        array_map(function ($class) {
            $this->make($class)->boot($this);
        }, [
            \Core\ServerProvide\EnvironmentProvider::class,
            \Core\ServerProvide\ConfigurationProvider::class,
            \Core\ServerProvide\FacadeRegisterProvide::class,
            \Core\ServerProvide\RouteProvider::class,
        ]);
    }

    /**
     * 获取配置文件名
     * @return string
     * IF I CAN GO DEATH, I WILL
     */
    public function getEnvironmentFileName()
    {
        return $this->EnvironmentFileName;
    }

    /**
     * 设置环境文件名
     * @param string $filename
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    public function setEnvironmentFileName(string $filename)
    {
        $this->EnvironmentFileName = $filename;
    }

    /**
     * 设置配置文件
     * @param string $key
     * @param mixed $value
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    public function setConfig(string $key, $value)
    {
        $key = explode('.', $key, 2);
        if (count($key) == 2) {
            $arr_key = $key[1];
            $name    = $key[0];
        } else {
            $arr_key = null;
            $name    = $key[0];
        }
        if ($arr_key) {
            $this->config[$name][$arr_key] = $value;
        }else{
            $this->config[$name] = $value;
        }
    }

    /**
     * 获取配置文件
     * @param string $key
     * @return mixed
     * IF I CAN GO DEATH, I WILL
     */
    public function getConfig(string $key = null)
    {
        $key = explode('.', $key, 2);
        if (count($key) == 2) {
            $name    = $key[0];
            $arr_key = $key[1];
        } else {
            $name    = $key[0];
            $arr_key = null;
        }
        if (!isset($this->config[$name])) {
            $this->loadConfig($name);
        }
        if ($arr_key) {
            if (!isset($this->config[$name][$arr_key])) {
                $this->loadConfig($name);
            }
            return $this->config[$name][$arr_key];
        }
        return $this->config[$name] ?? [];
    }

    /**
     * 加载配置文件到堆栈中
     * @param string $name
     * @return void
     * Real programmers don't read comments, novices do
     */
    protected function loadConfig(string $name)
    {
        $path = $this->instances['app.configPath'] . $name . '.php';
        if (file_exists($path)) {
            
            $this->config[$name] = array_merge(
                isset($this->config[$name])?$this->config[$name] : [],
                include($path)
            );
        }else{
            throw new FileNotFoundException($path);
        }
    }
}
