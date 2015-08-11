<?php
namespace Libs;

use Respect\Validation\Validator;

/**
 * app的工厂类
 * 负责生产apps下面的类
 * 在工厂中解决版本之间的继承问题
 * 注意：里面产生的类均会控制为单例模式，提升性能
 * @static
 */
class AppFactory
{
    /**
     * 存放实例化对象
     * @var array
     */
    private static $instanses = array();
    
    /**
     * 获取api实例化类
     * 如果配置了继承的版本，则会递归向上找，找不到则会抛出异常
     * @param string $className 类名
     * @param string $version 版本号，如果为空，则会自动获取当前版本号
     * @throws \Exception
     * @return object
     */
    public static function api($className,$version = null)
    {
        return self::getInstance('apis', $className, $version);
    }

    /**
     * 获取module对象
     * @param string $className module 类名
     * @return object
     */
    public static function module($className)
    {
        return self::getInstance('modules', $className);
    }

    /**
     * 获取model对象
     * @param $className model的类名
     * @return object
     */
    public static function model($className)
    {
        return self::getInstance('models', $className);
    }

    /**
     * 获取config下的配置对象
     * @param $className config类名
     * @return object
     */
    public static function config($className, $version = null)
    {
        return self::getInstance('config', $className, $version);
    }
    
    /**
     * 获取实例
     * @param string $layerName 三层结构中的名称
     * @param string $className 类名
     * @param string $version 版本号
     * @return object
     */
    private static function getInstance($layerName, $className, $version = null)
    {
        //判断版本号是否正确
        if ($version == '' || !is_string($version)) {
            $version = self::getVersion();
        }
        
        //判断是否已存在实例化对象了
        $instanceKey = "{$version}.{$layerName}.{$className}";
        if (!empty(self::$instanses[$instanceKey])) {
            //已存在此实例化对象了
            return self::$instanses[$instanceKey];
        }

        //获取实例
        $instance = self::createInstance($layerName, $className, $version);

        //最后再判断下是否已存在了，防止高并发下产生多个实例
        if (!empty(self::$instanses[$instanceKey])) {
            //已存在此实例化对象了
            return self::$instanses[$instanceKey];
        }

        //存入到实例中
        self::$instanses[$instanceKey] = $instance;

        return $instance;
    }
    
    /**
     * 生产实例
     * @param string $appName apis/modules/models
     * @param string $className
     * @param string $version
     * @throws \Exception
     * @return object
     */
    private static function createInstance($appName, $className, $version)
    {
        if ($version == '') {
            //自动获取
            $version = self::getVersion();
        }

        //判断类文件是否存在
        $classNameLower = strtolower($className);

        //判断是否是获取的配置，如果应该加载当前环境配置文件
        $versionUpper = strtoupper($version);
        //类空间名称
        $classNameSpace = '';
        if ($appName == 'config') {
            //是配置文件
            $env = RUN_MOD;
            $envUpper = ucfirst($env);
            $classFilePath = ROOT_PATH."/apps/{$version}/{$appName}/{$env}/{$classNameLower}.php";
            $classNameSpace = "\\Apps\\{$version}\\{$appName}\\{$envUpper}\\{$className}";
        } else {
            $classFilePath = ROOT_PATH."/apps/{$version}/{$appName}/{$classNameLower}.php";
            $classNameSpace = "\\Apps\\{$version}\\{$appName}\\{$className}";
        }

        if (!file_exists($classFilePath)) {
            //类文件不存在，则判断是否还要向上版本找
            $extendsVersion  = self::config('Config',$version)->extends;
            if (Validator::string()->notEmpty($extendsVersion)) {
                //配置了继承关系，递归向上找
                return self::createInstance($appName, $className, $extendsVersion);
            } else {
                //找不到则会抛出异常
                throw new \Exception("class {$className} not found");
            }
        }

        //找到了类，则实例化返回
        return new $classNameSpace();
    }
    
    /**
     * 获取当前调用者版本号
     */
    private static function getVersion()
    {
        //获取调用者文件名
        $debugInfo = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        if (empty($debugInfo[1]['file'])) {
            //指定是否某个不合法的地方调用了
            throw new \Exception('not found version');
        }
        $callerFile = $debugInfo[1]['file'];
        //获取版本号
        $callerFile = str_replace(ROOT_PATH.'/apps/', '', $callerFile);
        $version = substr($callerFile,0,strpos($callerFile, '/'));
        return $version;
    }
}
