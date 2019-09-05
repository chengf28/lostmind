<?php
namespace Core\Facade;

use Core\Facade\Facade;


class App extends Facade
{
    protected static function getFacadeName()
    {
        return "app";
    }
}
