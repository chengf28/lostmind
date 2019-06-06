<?php
namespace Core;
use Core\Container\Container;

class Application extends Container
{
    protected $rootPath;
    public function __construct(string $path)
    {
        $this->rootPath = trim($path,'\\').DIRECTORY_SEPARATOR;
        // 注册自己
        $this->bind('app',$this);
    }

    /**
     * 开始
     * @return void
     * God Bless the Code
     */
    public function start()
    {
        // 框架内容注册
        $this->baseRegister();

        $this->loadEnvironment();
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

    
    protected function loadEnvironment()
    {

    }

    protected function loadConfig()
    {

    }
}
