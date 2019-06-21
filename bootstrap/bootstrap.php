<?php
use Core\Application;
use Core\Request\Request;

#-----------------------------
# 初始化
#-----------------------------
require_once __ROOT__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$app = new Application(__ROOT__);
// 错误与异常处理注册
$app->instances('ExcepthonHandler', new Core\Exception\ExceptionHandler);
// 开始
$app->start();
