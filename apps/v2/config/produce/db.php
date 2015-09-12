<?php
/**
 * 指定环境的配置文件
 */
$config = [
    'mysql' => [
        'driver' => 'mysql',
        'read' => [
            'host' => '192.168.0.2',
        ],
        'write' => [
            'host' => '192.168.0.3'
        ],
        'database' => 'test',
        'username' => 'root',
        'password' => '123456',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => 'test_',
    ]
];

return $config;