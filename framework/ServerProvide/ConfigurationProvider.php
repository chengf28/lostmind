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

    /**
     * 处理DBquery需要的数据库类型
     * @return void
     * Real programmers don't read comments, novices do
     */
    protected function prepareDBconfig()
    {
        $need = [
            'host',
            'port',
            'dbname',
        ];
        // 加载配置信息
        $config = $this->app->getConfig('databases');
        if (!isset($config[$config['db_type']])) {
            throw new \InvalidArgumentException("Can't not found databases type which is `{$config['db_type']}`");
        }
        /**
         * 读取配置文件
         */
        $dsn = [];
        foreach ($config[$config['db_type']] as $key => $value) 
        {
            if ($key == 'database') {
                $key = 'dbname';
            }
            
            if ($value = explode(',',$value))
            {
                if (in_array($key, $need)) { 
                    $dsn[$key] = array_map(function($item) use ($key)
                    {
                        return "$key=$item";
                    },$value);
                }else{
                    $dsn[$key] = $value;
                }
            }
        }
        
        var_dump($dsn);

        die;
    }
}
