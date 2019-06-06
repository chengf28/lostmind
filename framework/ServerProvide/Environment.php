<?php
namespace Core\ServerProvide;
use Core\ServerProvide\ServerProvideAbstract;

class Environment extends ServerProvideAbstract
{
    public function start()
    {
       var_dump($this->app->make('app.configPath'));
    }

    public function getFile()
    {
        
    }
}
