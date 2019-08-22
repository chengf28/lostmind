<?php

namespace Core;

use Core\Container\Container;

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
        $this->bind('app',Application::class);
        $this->instances(Application::class,$this);
        $this->instances('app.rootPath', $this->rootPath);
        $this->instances('app.envPath', $this->rootPath);
        $this->instances('app.configPath', $this->rootPath . 'config' . DIRECTORY_SEPARATOR);
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
        ]
            as $abstract => $concrete) {
            $this->singleBind($abstract, $concrete);
        }


        // 工具类别名注册
        foreach ([
            // 文件操作系统
            'file' => \Core\Filesystem\Filesystem::class,
            // 路由构建
            'route' => \Core\Router\RouteGenerator::class,
        ]
            as $abstract => $concrete) {
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
        $this->config[$key] = $value;
    }

    /**
     * 获取配置文件
     * @param string $key
     * @return mixed
     * IF I CAN GO DEATH, I WILL
     */
    public function getConfig(string $key = null)
    {
        if (!$key) {
            return $this->config;
        }

        if (strpos($key, '.') !== false) {
            list($name, $namekey) = explode('.', $key, 2);
            return $this->config[$name][$namekey] ?? [];
        }

        return $this->config[$key] ?? [];
    }
}
