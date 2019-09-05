<?php
use Core\Application;
use Core\Request\Request;

#-----------------------------
# 初始化
#-----------------------------
require_once __ROOT__.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

require_once __ROOT__.'framework'.DIRECTORY_SEPARATOR.'Helper.php';

$app = new Application(__ROOT__);

// 开始
$app->start()->send();
