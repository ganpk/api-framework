<?php
namespace Lib;

use Respect\Validation\Validator;
/**
 * app的工厂类
 * 负责生产apps下面的类
 * 在工厂中解决版本之间的继承问题
 * TODO:版本之间的继承，判断类中是否存在某方法，没有去配置的继承版本中拿 ，同理向上推。
 * @static
 */
class AppFactory
{
    //TODO:api，modelus,model单例模式处理
    //TODO:静态类应该返回命名空间，如config，思考config和lib是否需要实现继承
    private static $apiInstanse = null;
    
    /**
     * 获取api实例化类
     * 如果配置了继承的版本，则会递归向上找，找不到则会抛出异常
     * @param string $className 类名
     * @param string $version 版本号，如果为空，则会自动获取当前版本号
     * @throws \Exception
     */
    public static function api($className,$version = null)
    {
        return self::getInstance('apis', $className, $version);
    }
    
    public static function module($className,$version = null)
    {
        return self::getInstance('modules', $className, $version);
    }
    
    public static function model($className,$version = null)
    {
        return self::getInstance('models', $className, $version);
    }
    
    public static function lib($className,$version = null)
    {
        return self::getInstance('models', $className, $version);
    }
    
    public static function config($className,$version = null)
    {
        return self::getInstance('config', $className, $version);
    }
    
    /**
     * 获取实例
     * @param string $appName apis/modules/models
     * @param string $className
     * @param string $version
     * @throws \Exception
     */
    private static function getInstance($appName, $className, $version = null)
    {
        if ($version == '') {
            //自动获取
            $version = self::getVersion();
        }
        $$classNameLower = strtolower($className);
        $classFilePath = ROOT_PATH."/apps/{$version}/{$appName}/{$classNameLower}.php".PHP_EOL;
        echo $classFilePath;
        if (!file_exists($classFilePath)) {
            //类文件不存在
            $configNameSpace = "\\Apps\\{$version}\\Config\\Config";
            var_dump($configNameSpace);
            $extendsVersion = $configNameSpace::$extends;
            if (Validator::string()->notEmpty($extendsVersion)) {
                //配置了继承关系，递归向上找
                return self::api($extendsVersion, $className);
            } else {
                //找不到则会抛出异常
                throw new \Exception("class {$className} not found");
            }
        }
        //找到了类，则实例化返回
        $classNameSpace = "\\Apps\\{$version}\\{$appName}\\{$className}";
        return new $classNameSpace();
    }
    
    /**
     * 获取当前调用者版本号
     */
    private static function getVersion()
    {
        //获取调用者文件名
        $debugInfo = (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2));
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
