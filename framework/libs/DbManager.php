<?php
namespace Framework\Libs;

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
        $projectName = ucfirst(APP_NAME);
        $configClass = "\\Apps\\{$projectName}\\Config\\Db";
        $capsule->addConnection($configClass::instance()->mysql);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}