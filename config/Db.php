<?php
namespace Config;

/**
 * ���ݿ�����
 * �����һ������һ�����ӣ����뵽���ӳ��У�\Config\Server�����õ�
 * ����ж������ᰴ��æ�����Զ��ַ�
 * Class Db
 * @package Config
 */
class Db
{
    /**
     * mysql����
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