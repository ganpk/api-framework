<?php
namespace Config;

/**
 * 数据库配置
 * 程序会一次生成一堆连接，放入到连接池中，\Config\Server中配置的
 * 如果有多个，则会按闲忙程序自动分发
 * Class Db
 * @package Config
 */
class Db
{
    /**
     * mysql配置
     * @var array
     */
//    public static $mysql = array(
//        'read' => [
//            '192.168.1.1',
//        ],
//        'write' => [
//            '196.168.1.2'
//        ],
//        'database'  => 'database',
//        'username'  => 'root',
//        'password'  => '',
//        'charset'   => 'utf8',
//        'collation' => 'utf8_unicode_ci',
//        'prefix'    => '',
//    );

    public static $mysql = [
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
    ];

}