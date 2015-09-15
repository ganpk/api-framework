<?php
namespace Framework\Libs;

/**
 * 测试基类
 * Class Test
 * @package Framework\Libs
 */
abstract class Test extends \PHPUnit_Framework_TestCase
{
    /**
     * 构造方法
     */
    final public function __construct() {
        $this->init();
    }

    /**
     * 初使化测试的上下文环境
     */
    protected function init()
    {
        //定义框架路径
        define('FRAMEWORK_PATH', dirname(__DIR__));
        //定义应用路径
        define('APP_PATH', dirname(FRAMEWORK_PATH).'/app');
        require FRAMEWORK_PATH.'/bootstrap/initWorker.php';
    }
}