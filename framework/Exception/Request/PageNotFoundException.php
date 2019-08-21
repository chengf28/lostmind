<?php
namespace Core\Exception\Request;

use ErrorException;

class PageNotFoundException extends ErrorException
{
    public function __construct(string $page)
    {
        parent::__construct("`$page` is not found",404);
    }
}
