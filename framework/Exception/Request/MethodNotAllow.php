<?php

namespace Core\Exception\Request;

use ErrorException;

class MethodNotAllow extends ErrorException
{
    public function __construct($method)
    {
        parent::__construct("the $method method is not allow");
    }   
}