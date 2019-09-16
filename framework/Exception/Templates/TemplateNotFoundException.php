<?php

namespace Core\Exception\Templates;

use RuntimeException;

/**
 * 模板未找到异常
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class TemplateNotFoundException extends RuntimeException
{ 
    public function __construct(string $file,$suffix = null)
    {
        parent::__construct(basename($file, $suffix) . ' is not found at ' . dirname($file) . DIRECTORY_SEPARATOR);
    }
}
