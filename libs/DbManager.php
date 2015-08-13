<?php
namespace Libs;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
/**
 * 数据库管理类
 * Class Db
 * @package Libs
 */
class DbManager
{
    /**
     * 连接数据库
     * 这里并没有真正的连接，因为还没有使用，只是添加了连接信息
     */
    public static function connect()
    {
        $capsule = new Capsule();
        // 创建链接
        $capsule->addConnection(\Config\Db::$mysql);
        // 设置全局静态可访问
        $capsule->setAsGlobal();
        // 启动Eloquent
        $capsule->bootEloquent();
    }
}