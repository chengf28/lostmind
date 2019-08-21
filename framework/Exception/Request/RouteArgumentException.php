<?php
namespace Core\Exception\Request;

use InvalidArgumentException;

class RouteArgumentException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('The Routing Configuration Does Not Specify Controller Or Method');
    }
}
