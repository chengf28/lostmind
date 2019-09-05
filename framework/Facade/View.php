<?php
namespace Core\Facade;

use Core\Facade\Facade;


class View extends Facade
{
    protected static function getFacadeName()
    {
        return 'view';
    }
}
