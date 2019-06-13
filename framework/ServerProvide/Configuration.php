<?php
namespace Core\ServerProvide;
use Core\ServerProvide\ServerProvideAbstract;

class Configuration extends ServerProvideAbstract
{
    private $file;

    public function start()
    {
        $this->file = $this->app['file'];
        $this->getConfig();
    }

    
}