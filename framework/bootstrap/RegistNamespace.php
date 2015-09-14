<?php
namespace Core\Bootstrap;

/**
 * 注册根命名空间对应的目录关系到自动加载类中
 */
class RegistNameSpace
{
    /**
     * 自动加载类的loader对象
     * @var \Composer\Autoload\ClassLoader
     */
    private $classLoader = null;

    /**
     * 构造方法
     * @param string $rootPath
     * @param array $ignore
     * @param null $classLoader
     * @throws \Exception
     */
    public function __construct()
    {
        //引入自动加载类
        $loader = require FRAMEWORK_PATH . '/vendor/autoload.php';
        $this->classLoader = $loader;
    }

    /**
     * 开始注册
     * @return boolean
     */
    public function register()
    {
        $this->classLoader->getUseIncludePath();
        //注册framework目录
        $this->scanDirToClassMap(FRAMEWORK_PATH,'Framework',['vendor']);

        //注册app目录
        $this->scanDirToClassMap(APP_PATH,'App');
    }

    /**
     * 扫描目录下的目录并增加到class map中
     */
    private function scanDirToClassMap($dir,$rootNamespace,$ignore = array())
    {
        if (is_dir($dir)) {
            $dh = opendir($dir);
            if ($dh) {
                while (($file = readdir($dh)) !== false) {
                    $path = $dir . '/' . $file;
                    if (is_dir($path) && strpos($file, '.') !== 0 && !in_array($file,$ignore)) {
                        $namespace = $rootNamespace.'\\'.ucfirst($file) . '\\';
                        $this->classLoader->setPsr4($namespace, $path);
                    }
                }
                closedir($dh);
            } else {
                throw new \Core\Exceptions\RegistNamespaceException('不能找开目录' . $dir);
            }
        }
    }
}