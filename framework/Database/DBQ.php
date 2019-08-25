<?php

namespace Core\Database;

use Core\Application;

class DBQ
{
    /**
     * App核心
     * @var \Core\Application
     * Real programmers don't read comments, novices do
     */
    protected $app;
    public function __construct(Application $app)
    {
        $this->app = $app;
    }
}
