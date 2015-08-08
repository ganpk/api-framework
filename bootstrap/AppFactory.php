<?php
namespace Bootstrap;

/**
 * app的工厂类
 * 负责生产apps下面的类
 * 在工厂中解决版本之间的继承问题
 * TODO:版本之间的继承，判断类中是否存在某方法，没有去配置的继承版本中拿 ，同理向上推。
 * @static
 */
class AppFactory
{    
    /**
     * 获取api实例化类
     * @param string $className
     */
    public static function api($className)
    {
    }
    
    public static function module($className)
    {
    }
    
    public static function model($className)
    {
    }
    
    public static function config($className)
    {
    }
    
    public static function lib($className)
    {
    }
    
    private static function getClassPath()
    {
    }
}
