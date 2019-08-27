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
        $this->getBaseConfig();
      

        $this->prepareDBconfig();
    }

    /**
     * 加载配置文件
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    protected function getBaseConfig()
    {
        $basconfig = include($this->app['app.configPath'].'base.php');
        $this->app->setConfig('base',$basconfig);
        $this->app->setConfig('AppName', $basconfig['name']);
        date_default_timezone_set($basconfig['timezone']);
    }

    /**
     * 处理DBquery需要的数据库类型
     * @return void
     * Real programmers don't read comments, novices do
     */
    protected function prepareDBconfig()
    {
        $need = [
            'host'        => 'host',
            'port'        => 'port',
            'database'    => 'dbname',
            'db_username' => false,
            'db_password' => false,
        ];
        /**
         * 加载配置信息
         */
        $config = include($this->app['app.configPath'] . 'databases.php');
        $this->app->setConfig('databases', $config);
        if (!isset($config[$config['db_type']])) {
            throw new \InvalidArgumentException("Can't not found databases type which is `{$config['db_type']}`");
        }
        /**
         * 读取配置文件
         */
        if (isset($config[$config['db_type']]['write']) && isset($config[$config['db_type']]['read'])) {
            $raw_config = $config[$config['db_type']];
            unset($raw_config['prefix']);
        } else {
            $raw_config['read'] = $raw_config['write'] = $config[$config['db_type']];
        }
        $parepre_config = array_map(function ($raw) use ($need) {
            $dsn_array   = [];
            $certificate = [];
            foreach ($need as $name => $keyname) {
                $value_arr = explode(',', $raw[$name]);
                if ($keyname !== false) {
                    $value_arr = array_map(function ($item) use ($keyname) {
                        return "$keyname=$item;";
                    }, $value_arr);
                    if ($dsn_array) {
                        if (count($dsn_array) < count($value_arr)) {
                            $tmp       = $value_arr;
                            $value_arr = $dsn_array;
                            $dsn_array = $tmp;
                            unset($tmp);
                        }
                        foreach ($dsn_array as $k => &$v) {
                            $v .= isset($value_arr[$k]) ? $value_arr[$k] : end($value_arr);
                        }
                        unset($v);
                    } else {
                        $dsn_array = $value_arr;
                    }
                } else {
                    $certificate[$name] = $value_arr;
                }
            }
            return array_merge(['dsn'  => $dsn_array],$certificate);
            
        }, $raw_config);
        $parepre_config['prefix'] = $config[$config['db_type']]['prefix'];
        $parepre_config['type'] = $config['db_type'];
        $this->app->setConfig('DBconfig', $parepre_config);
    }
}
