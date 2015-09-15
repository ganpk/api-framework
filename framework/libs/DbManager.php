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
        self::$capsule = new Capsule();
        self::$capsule->addConnection(\App\Config\Db::instance()->mysql);
        self::$capsule->setAsGlobal();
        self::$capsule->bootEloquent();
        self::$capsule->getDatabaseManager()->disconnect();
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