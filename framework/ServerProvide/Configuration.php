<?php
namespace Core\ServerProvide;
use Core\ServerProvide\ServerProvideAbstract;

class Configuration extends ServerProvideAbstract
{
    private $file;

    /**
     * 开始
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    public function start()
    {
        $this->file = $this->app['file'];
        $this->getConfig();
    }

    /**
     * 加载配置文件
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    public function getConfig()
    {
        foreach (glob($this->app['app.configPath'].'*.php') as $file)
        {
            $this->app->setConfig(basename($file,'.php'),include $file);
        }
    }
}