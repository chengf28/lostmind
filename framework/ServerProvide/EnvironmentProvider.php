<?php

namespace Core\ServerProvide;

use Core\ServerProvide\ServerProvideAbstract;

/**
 * 环境文件加载服务
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class EnvironmentProvider extends ServerProvideAbstract
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
        $this->getFile();
    }

    /**
     * 读取文件
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    public function getFile()
    {
        if ($this->file->has($filename = $this->app['app.envPath'] . $this->app->getEnvironmentFileName())) {
            foreach ($this->file->getLine($filename) as $content) {
                // 跳过注释内容
                if (strpos($content, '#') === 0 || strpos($content, '=') === false) continue;
                $this->setEnv(...explode('=', $content, 2));
            }
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
}
