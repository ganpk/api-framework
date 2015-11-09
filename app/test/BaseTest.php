<?php
namespace App\Test;
require '../../framework/libs/Test.php';

/**
 * 测试基类
 */
abstract class BaseTest extends \Framework\Libs\Test
{
    /**
     * 调用API接口
     * @param unknown $class
     * @param unknown $method
     * @param array $post
     * @param array $header
     * @return array
     */
    public function callApi($class, $method, $post = array(), $header = array())
    {
        $classPath = '\App\Apis\\'.$class;
        $obj = new $classPath();
        $obj->params = $post;
        $data = $obj->{$method}();
        $data = \Framework\Libs\Utility::converToHump($data);
        return $data;
    }
}