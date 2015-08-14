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
    abstract static function instance();

    /**
     * 实例化子类
     * @return BaseConfig
     */
    public function getObj()
    {
        if (self::$instance == null) {
            //实例化子类
            $class = get_called_class();
            $currrentObj = new $class();
            //获取属性
            $vars = get_object_vars($currrentObj);
            //获取是否配置了当前环境的配置项

            //保存属性到_propertys中
            $currrentObj->_propertys = $vars;
            if (!empty($vars)) {
                foreach ($vars as $k => $v) {
                    unset($currrentObj->{$k});
                }
            }
        }
        return $currrentObj;
    }
}