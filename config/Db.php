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
    public static $mysql = array(
        'read' => [
            '192.168.1.1',
        ],
        'write' => [
            '196.168.1.2'
        ],
        'database'  => 'database',
        'username'  => 'root',
        'password'  => '',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    );
}