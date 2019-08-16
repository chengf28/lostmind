<?php

namespace Core\Facade;

use Core\Facade\Facade;

class Route extends Facade
{
    protected static function getFacadeName()
    {
        return \Core\Router\Router::class;
    }
}
