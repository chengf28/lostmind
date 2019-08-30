<?php

return [
    /**
     * 应用名 
     * Application Name
     */
    'name'         => 'LostMind',

    /**
     * 是否为DEBUG模式
     * debug mode
     */
    'debug'        => true,

    /**
     * 时区 
     * Time Zone
     */
    'timezone'     => 'Asia/Shanghai',

    /**
     * SSL请求证书地址 
     * SSL Cert Path
     */
    'ssl_cert'     => '',

    /**
     * SSL正式密码  
     * SSL Cert Password
     */
    'ssl_password' => '',

    /**
     * 日志存放位置
     * Log storage location
     */
    'log_storage'  => __ROOT__ . 'storage/logs',

    /**
     * 模板视图存放位置
     * Template View storage location
     */
    'templates'    => __ROOT__ . 'storage/cache/templates',
];
