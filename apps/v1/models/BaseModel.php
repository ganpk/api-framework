<?php
namespace v1\modules;

/**
 * 模型的基类
 * TODO:ORM选择和实现，读写分离的数据库配置，注册基本cli下的连接，之前陈俊做的是一个模型使用一个连接，且开启了持久连接，这里需要思考下
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