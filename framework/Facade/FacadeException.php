<?php

namespace Core\Facade;

use RuntimeException;

class FacadeException extends RuntimeException
{
    public function __construct($name)
    {
        parent::__construct("The Class {$name} Is Not Exists");
    }
}
