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
    /**
     * 开始
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    public function start()
    {
        foreach (glob($this->app['app.configPath'] . '*.php') as $file) 
        {
            $this->app->setConfig(
                basename($file, '.php'),
                include $file
            );
        }
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
        $basconfig = $this->app->getConfig('base');
        $this->app->setConfig('base', $basconfig);
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
        $config = $this->app->getConfig('databases');
        $this->app->setConfig('databases', $config);
        if (!isset($config[$config['db_type']])) {
            throw new \Core\Exception\Databases\InvalidConfigArgumentException("Can't not found databases type which is `{$config['db_type']}`");
        }
        /**
         * 读取配置文件
         */
        $type   = $config['db_type'];
        $config = $config[$config['db_type']];
        if (isset($config['write']) && isset($config['read'])) {
            $raw_config['write'] = $config['write'];
            $raw_config['read']  = $config['read'];
        } else {
            $raw_config['read'] = $raw_config['write'] = $config;
        }
        $parepre_config = array_map(function ($raw) use ($need, $config) 
        {
            $dsn_array   = [];
            $certificate = [];
            foreach ($need as $name => $keyname) {
                if (!isset($raw[$name])) 
                {
                    if (!isset($config[$name])) 
                    {
                        throw new \Core\Exception\Databases\InvalidConfigArgumentException("Databases configuration miss parameter $name");
                    }
                    $raw[$name] = $config[$name];
                }
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
            return array_merge(['dsn'  => $dsn_array], $certificate);
        }, $raw_config);
        $parepre_config['prefix'] = $config['prefix'];
        $parepre_config['type']   = $type;
        $this->app->setConfig('DBconfig', $parepre_config);
    }
}
