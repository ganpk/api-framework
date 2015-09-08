<?php
namespace Core\Libs;

/**
 * 配置文件基类
 * Class BaseConfig
 * @package Apps\Item\Config
 */

abstract class AppBaseConfig
{
    //存储当前对象
    protected static $instance = null;

    /**
     * 子类必须实现获取实例方法
     * 了类直接调用getObj方法即可，这样做的另一个目的是子类中可以打return 注释，在使用时才会有提示
     * @return mixed
     */

    /**
     * 实例化子类
     * @return BaseConfig
     */
    public static function instance()
    {
        if (self::$instance == null) {
            //实例化子类
            $class = get_called_class();
            self::$instance = new $class();
            //获取当前环境配置文件路径
            $class = ltrim($class, '\\');
            $classPathArr = explode('\\', $class);
            $classPathArr[4] = $classPathArr[3] . '.php';
            $classPathArr[3] = RUN_MOD;
            $filePath = ROOT_PATH . '/' . strtolower(implode('/', $classPathArr));
            if (file_exists($filePath)) {
                //存在指定环境配置文件
                $configs = require $filePath;
                //使用环境配置项覆盖全局配置项
                $vars = get_object_vars(self::$instance);
                if (!empty($configs) && is_array($configs)) {
                    foreach ($configs as $k => $v) {
                        self::$instance->{$k} = $v;
                    }
                }
            }
        }
        return self::$instance;
    }
}