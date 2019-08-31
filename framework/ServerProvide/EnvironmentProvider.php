<?php

namespace Core\ServerProvide;

use Core\Filesystem\Filesystem;
use Core\ServerProvide\ServerProvideAbstract;

/**
 * 环境文件加载服务
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class EnvironmentProvider extends ServerProvideAbstract
{

    /**
     * 开始
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    public function start()
    {
        $this->getFile();
        // 创建环境需要目录

        $this->mkEnvdir();
    }

    /**
     * 读取文件
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    public function getFile()
    {

        if (Filesystem::has($filename = $this->app['app.envPath'] . $this->app->getEnvironmentFileName())) {
            $file = new Filesystem($filename);
            foreach ($file->line() as $content) {
                // 跳过注释内容
                if (strpos($content, '#') === 0 || strpos($content, '=') === false) continue;
                $this->setEnv(...explode('=', $content, 2));
            }
            $file = null;
        }
    }

    /**
     * 将环境参数加载到内存中
     * @param string $key
     * @param mixed $value
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    public function setEnv(string $key, $value)
    {
        function_exists('apache_setenv') && apache_setenv($key, $value);
        function_exists('putenv') && putenv("{$key}={$value}");
        $_ENV[$key] = $value;
    }

    /**
     * 创建目录
     * @return void
     * Real programmers don't read comments, novices do
     */
    protected function mkEnvdir()
    {

        if (!Filesystem::has($log_dir = $this->app->getConfig('base.log_storage'))) {
            Filesystem::dir($log_dir);
        }
        if (!Filesystem::has($templates_dir = $this->app->getConfig('base.templates_storage'))) {
            Filesystem::dir($templates_dir);
        }
        unset($log_dir, $templates_dir);
    }
}
