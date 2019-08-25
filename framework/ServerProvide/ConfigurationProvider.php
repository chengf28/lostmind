<?php

namespace Core\ServerProvide;

use Core\ServerProvide\ServerProvideAbstract;

/**
 * 配置信息加载服务
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class ConfigurationProvider extends ServerProvideAbstract
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

        $basconfig = $this->app->getConfig('base');
        $this->app->setConfig('AppName', $basconfig['name']);

        date_default_timezone_set($basconfig['timezone']);

        $this->prepareDBconfig();

    }

    /**
     * 加载配置文件
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    protected function getConfig()
    {
        foreach (glob($this->app['app.configPath'] . '*.php') as $file) {
            $this->app->setConfig(
                basename($file, '.php'),
                include $file
            );
        }
    }

    protected function prepareDBconfig()
    {
        // 加载配置信息
        $config = $this->app->getConfig('databases');
        if (isset($config[$config['db_type']])) {
            /**
             * 读取配置文件
             */
            $config = $config[$config['db_type']];
        }
    }
}
