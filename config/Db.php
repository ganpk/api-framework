<?php
namespace Config;

/**
 * 数据库配置
 * $env里面key为运行环境名称
 * Class Db
 * @package Config
 */
class Db
{
    /**
     * 各环境下的配置
     * @var array
     */
    public static $environments = [
        'dev' => [
            'driver'    => 'mysql',
            'read' => [
                'host' => '127.0.0.1',
            ],
            'write' => [
                'host' => '127.0.0.1'
            ],
            'database'  => 'test',
            'username'  => 'root',
            'password'  => '123456',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => 'test_',
        ],
    ];
}