<?php
namespace Core\Libs;

/**
 * IOC容器
 * Class Ioc
 * @package Core\Libs
 */
class Ioc
{
    public static $containers = array();

    /**
     * 绑定容器
     * @param $serverName
     * @param $callBack
     */
    public static function bind($serverName, $callBack)
    {
        self::$containers[$serverName] = $callBack;
    }

    /**
     * 取出容器
     * @param $serverName
     */
    public static function make($serverName)
    {
        $callBack = self::$containers[$serverName];
        return $callBack();
    }
}