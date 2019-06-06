<?php
namespace Core\ServerProvide;
use Core\ServerProvide\ServerProvideAbstract;

class Environment extends ServerProvideAbstract
{
    public function start()
    {
        $this->app['file'];
    }
}
