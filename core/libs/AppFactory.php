<?php
namespace Libs;

use Respect\Validation\Validator;

/**
 * app的工厂类
 * 负责生产apps下面的类
 * 在工厂中解决版本之间的继承问题
 * @static
 */
class AppFactory
{
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
        if ($version == null) {
            $version = self::getVersion();
        }
        return self::getInstance('apis', $className, $version);
    }

    /**
     * 获取module对象
     * @param string $className module 类名
     * @return object
     */
    public static function module($className)
    {
        $version = self::getVersion();
        return self::getInstance('modules', $className, $version);
    }

    /**
     * 获取model对象
     * @param $className model的类名
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function model($className)
    {
        $version = self::getVersion();
        return self::getInstance('models', $className, $version);
    }

    /**
     * 获取config下的配置对象
     * @param $className config类名
     * @return object
     */
    public static function config($className, $version = null)
    {
        if ($version == null) {
            $version = self::getVersion();
        }
        return self::getInstance('config', $className, $version);
    }
    
    /**
     * 生产实例
     * @param string $appName apis/modules/models
     * @param string $className
     * @param string $version
     * @throws \Exception
     * @return object
     */
    private static function getInstance($appName, $className, $version)
    {
        if ($version == null) {
            throw new \Exception('未发现版本号');
        }
        //判断类文件是否存在
        $classNameLower = strtolower($className);

        //判断是否是获取的配置，如果应该加载当前环境配置文件
        //类空间名称
        $classNameSpace = '';
        if ($appName == 'config') {
            //获取配置
            //优先找当前环境下的配置，没有则找全局下的配置
            $env = RUN_MOD;
            $envUpper = ucfirst($env);
            $classFilePath = ROOT_PATH."/apps/{$version}/{$appName}/{$env}/{$classNameLower}.php";
            $classNameSpace = "\\Apps\\{$version}\\{$appName}\\{$envUpper}\\{$className}";
            if (!file_exists($classFilePath)) {
                //定制环境中有配置文件，则拿全局的
                $classFilePath = ROOT_PATH."/apps/{$version}/{$appName}/{$classNameLower}.php";
                $classNameSpace = "\\Apps\\{$version}\\{$appName}\\{$className}";
            }
        } else {
            $classFilePath = ROOT_PATH."/apps/{$version}/{$appName}/{$classNameLower}.php";
            $classNameSpace = "\\Apps\\{$version}\\{$appName}\\{$className}";
        }

        if (!file_exists($classFilePath)) {
            //类文件不存在，则判断是否还要向上版本找
            $extendsVersion  = self::config('Version',$version)->extends;
            if ($extendsVersion != null) {
                //配置了继承关系，递归向上找
                return self::getInstance($appName, $className, $extendsVersion);
            } else {
                //找不到则抛出异常
                throw new \Exception("class {$className} not found");
            }
        }

        //找到了类
        if ($appName == 'config') {
            //config不是单例模式，new后返回
            return new $classNameSpace();
        } elseif ($appName == 'models') {
            //如果获取的是model，则返回此类的空间路径
            return $classNameSpace;
        } else {
            return $classNameSpace::instance();
        }
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
