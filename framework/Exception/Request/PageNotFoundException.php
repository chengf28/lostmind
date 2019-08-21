<?php
namespace Core\Exception\Request;

use ErrorException;

class PageNotFoundException extends ErrorException
{
    public function __construct(string $page)
    {
        parent::__construct("The Page $page Is Not Found",404);
    }

    public function getHtml()
    {
        return "Page Not Found";
    }
}
