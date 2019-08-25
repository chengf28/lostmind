<?php
return [
    /**
     * 数据库类型
     */
    'db_type' => 'mysql',
    /*
    |---------------------------------------
    | mysql数据库配置
    |---------------------------------------
    | 如果是多库配置请使用 `,` 符号分隔
    | If it is a multi-library configuration, 
    | use the symbol `,` to separate it
    */
    'mysql' => [
        /**
         * host
         */
        'host'        => '127.0.0.1',
        /**
         * 端口号
         */
        'port'        => 3306,
        /**
         * 数据库名
         */
        'db_name'     => 'Test',
        /**
         * 数据库用户名 
         */
        'db_username' => 'Test',
        /**
         * 数据库密码
         */
        'db_password' => 'Test',
        /**
         * 前缀
         */
        'prefix'      => '',
    ]
];