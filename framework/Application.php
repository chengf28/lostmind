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
        $this->rootPath = trim($path,'\\').DIRECTORY_SEPARATOR;
        // 注册自己
        $this->bind('app',$this);
        $this->instances('app.rootPath',$this->rootPath);
        $this->instances('app.envPath',$this->rootPath);
        $this->instances('app.configPath',$this->rootPath.'config'.DIRECTORY_SEPARATOR);
    }

    /**
     * 开始
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    public function start()
    {
        // 框架内容注册
        $this->baseRegister();

        $this->loadServerProvides();
    }


    protected function baseRegister()
    {
        foreach (
            [
                // 文件操作系统
                'file' => \Core\Filesystem\Filesystem::class
            ] 
        as $abstract => $concrete) 
        {
            $this->bind($abstract,$concrete);
        }
    }

    protected function loadServerProvides()
    {
        array_map(function($class)
        {
           $this->make($class)->boot($this);
        },[
            \Core\ServerProvide\Environment::class,
            \Core\ServerProvide\Configuration::class
        ]);
    }

    public function getEnvironmentFileName()
    {
        return $this->EnvironmentFileName;
    }

    public function setEnvironmentFileName(string $filename)
    {
        $this->EnvironmentFileName = $filename;
    }
    
    public function setConfig(string $name, string $key, $value)
    {
        $this->config["{$name}.{$key}"] = $value;
    }
}
