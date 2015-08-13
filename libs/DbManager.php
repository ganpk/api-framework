<?php
namespace Libs;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
/**
 * db管理类
 * Class Db
 * @package Libs
 */
class DbManager
{
    /**
     * 向orm中添加连接信息
     * 此时并未连接，如果有使用时才会连接
     */
    public static function connect()
    {
        $capsule = new Capsule();
        $runMod = RUN_MOD;
        $capsule->addConnection(\Config\Db::$environments[$runMod]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}