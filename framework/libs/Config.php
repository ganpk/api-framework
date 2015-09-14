<?php
namespace Framework\Libs;

/**
 * 配置文件基类
 * Class BaseConfig
 * @package Framework\Libs
 */

abstract class Config
{
    //存储配置的实例化对象
    protected static $instances = array();

    //禁止外部实例化
    final protected function __construct(){}

    /**
     * 实例化子类
     * @return AppBaseConfig
     */
    public static function instance()
    {
        $class = get_called_class();
        if (empty(self::$instances[$class])) {
            //实例化子类
            self::$instances[$class] = new $class();

            //获取当前环境配置文件路径
            $class = ltrim($class, '\\');
            $classPathArr = explode('\\', $class);
            $configClassName = array_pop($classPathArr);
            array_shift($classPathArr);
            $filePath = APP_PATH;
            foreach ($classPathArr as $v) {
                $filePath .= '/'.strtolower($v);
            }
            $filePath .= '/'.RUN_MOD."/{$configClassName}.php";

            //如果存在指定环境的配置文件，则使用环境配置项覆盖全局配置项
            if (file_exists($filePath)) {
                //存在指定环境配置文件
                $configs = require $filePath;
                //使用环境配置项覆盖全局配置项
                if (!empty($configs) && is_array($configs)) {
                    foreach ($configs as $k => $v) {
                        self::$instances[$class]->{$k} = $v;
                    }
                }
            }
        }
        return self::$instances[$class];
    }
}