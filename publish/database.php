<?php

return [
    'default'     => 'mysql',
    'connections' => [
        'mysql' => [
            //驱动
            'driver'   => '\Yng\Database\Connectors\MySqlConnector',
            //可以使用dsn来配置更多参数，会优先使用该参数
            'dsn'      => '',
            //主机地址
            'host'     => env('db_host', 'localhost'),
            //数据库用户名
            'user'     => env('db_username', 'user'),
            //数据库密码
            'password' => env('db_password', 'pass'),
            //数据库名
            'database' => env('db_database', 'name'),
            //端口
            'port'     => env('db_port', 3306),
            //额外设置
            'options'  => [],
            //编码
            'charset'  => env('database . charset', 'utf8mb4'),
            //数据表前缀
            'prefix'   => '',
        ],
    ],


];
