<?php
namespace Core\Bootstrap;

/**
 * 注册根命名空间对应的目录关系到自动加载类中
 */
class RegistNameSpace
{
    /**
     * 存放当前实例化类
     * @var RegistNameSpace
     */
    private static $instance = null;
    
    /**
     * 是否已经处理过了
     * @var boolean
     */
    private static $isHandled = false;
    
    /**
     * 自动加载类的loader对象
     * @var ClassLoader
     */
    private $classLoader = null;
    
    /**
     * 要加入命名空间的根目录
     * @var string
     */
    private $rootPath = '';
    
    /**
     * 忽略的目录
     * @var array
     */
    private $ignore = array();
    
    /**
     * 单例模式禁止外部实例化
     * @param array $ignore
     * @param string $rootPath
     * @param string $classLoader
     */
    final protected function __construct($rootPath = '', $ignore = array(), $classLoader = null)
    {
        $this->rootPath = $rootPath;
        $this->ignore = $ignore;
        $this->classLoader = $classLoader;
    }
    
    /**
     * 单例模式禁止外部克隆
     */
    final protected  function __clone()
    {
    }
    
    /**
     * 获取实例化对象
     */
    public static function instance($rootPath = '',$ignore = array(), $classLoader = null)
    {
        if (!is_string($rootPath) || $rootPath == '') {
            throw new \Exception('rootPath参数只能是非常空字符串');
        }
        if (!is_array($ignore)) {
            throw new \Exception('ignore参数只能是数组');
        }
        if (!is_object($classLoader) || $classLoader == null) {
            throw new \Exception('classLoader 必须是classLoader类的实例化对象');
        }
        if (self::$instance == null) {
            self::$instance = new self($rootPath, $ignore, $classLoader);
        }
        return self::$instance;
    }
    
    /**
     * 销毁实例化对象
     */
    public static function desInstance()
    {
        if (self::$instance != null){
            self::$instance =  null;
        }
    }
    
    /**
     * 开始注册
     * @return boolean
     */
    public function register()
    {
        if (self::$isHandled) {
            //已经处理过了，不再重复处理
        } else {
            //扫描目录下的目录并增加到class map中
            $this->scanDirAddClassMap($this->rootPath);
            //注册完后销毁对象，防止浪费内存
            self::desInstance();
        }
    }
    
    /**
     * 扫描目录下的目录并增加到class map中
     */
    private function scanDirAddClassMap($dir)
    {
        if (is_dir($dir)) {
            $dh = opendir($dir);
            if ($dh) {
                while (($file = readdir($dh)) !== false) {
                    $path = $dir.'/'.$file;
                    if (is_dir($path) && strpos($file, '.') !==0 ){
                        $namespace = ucfirst($file).'\\';
                        $this->classLoader->setPsr4($namespace,$path);
                    }
                }
                closedir($dh);
                self::$isHandled = true;
            } else {
                throw new \Exceptions\RegistNamespaceException('不能找开目录'.$dir);
            }
        }
    }
}