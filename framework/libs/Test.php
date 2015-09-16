<?php
namespace Framework\Libs;

//定义框架路径
define('FRAMEWORK_PATH', dirname(__DIR__));
//定义应用路径
define('APP_PATH', dirname(FRAMEWORK_PATH).'/app');

require FRAMEWORK_PATH.'/bootstrap/initWorker.php';
/**
 * 测试基类
 * Class Test
 * @package Framework\Libs
 */
abstract class Test extends \PHPUnit_Framework_TestCase
{
    final public function __construct()
    {
        //连接数据库
        \Framework\Libs\DbManager::connect();
    }
}