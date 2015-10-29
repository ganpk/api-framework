<?php
namespace Framework\Libs;

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * db管理类
 * Class Db
 * @package Libs
 */
class DbManager
{
    /**
     * DB Manager实例
     * @var Capsule
     */
    static $capsule = null;

    /**
     * 向orm中添加连接信息
     * 此时并未连接，如果有使用时才会连接
     */
    public static function connect()
    {
        if (self::$capsule == null) {
            //连接数据库,此时还没有直接连接数据库，只是添加了一个配置，只有程序真正在需要使用数据库时才会连接
            self::$capsule = new Capsule();
            self::$capsule->addConnection(\App\Config\Db::instance()->mysql);
            self::$capsule->setAsGlobal();
            self::$capsule->bootEloquent();
        } else {
            //已经连接过了，则断开后下次再使用ORM会自动连接
        }
    }

    /**
     * 关闭mysql连接
     */
    public static function disconnect()
    {
        //关闭mysql连接
        if (!empty(self::$capsule)) {
            self::$capsule->getDatabaseManager()->disconnect();
        }
    }
}