<?php
namespace v1\modules;

/**
 * 模块的基类
 * TODO:模块的基类,1.封装公共方法
 */
class BaseModule
{
    /**
     * 存放当前实例化类
     * @var RegistNameSpace
     */
    private static $instance = null;
    
    /**
     * 单例模式禁止外部实例化
     */
    final protected function __construct()
    {
    }
    
    /**
     * 单例模式禁止外部克隆
     */
    final protected function __clone()
    {
    }
    
    /**
     * 获取实例化对象
     */
    public static function instance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}