<?php

namespace Apps\V1\Apis;

/**
 * API 基类
 * Class BaseApi
 * @package Apps\V1\Apis
 */
class BaseApi
{
    /**
     * 存放当前实例化类
     * @var Object HandlerNamespace
     */
    private static $instance = null;

    /**
     * 单例模式禁止外部实例化
     */
    final private function __construct()
    {
    }

    /**
     * 单例模式禁止外部克隆
     */
    final private function __clone()
    {
    }

    /**
     * 获取实例化对象
     * @return object
     */
    public static function instance()
    {
        if (self::$instance == null) {
            $class = get_called_class();
            self::$instance = new $class();
        }
        return self::$instance;
    }
}