<?php
namespace Libs;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
/**
 * ���ݿ������
 * Class Db
 * @package Libs
 */
class DbManager
{
    /**
     * �������ݿ�
     * ���ﲢû�����������ӣ���Ϊ��û��ʹ�ã�ֻ�������������Ϣ
     */
    public static function connect()
    {
        $capsule = new Capsule();
        // ��������
        $capsule->addConnection(\Config\Db::$mysql);
        // ����ȫ�־�̬�ɷ���
        $capsule->setAsGlobal();
        // ����Eloquent
        $capsule->bootEloquent();
    }
}