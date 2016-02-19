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
     * @param string  $dataType json | array
     * @return array
     */
    public function callApi($class, $method, $post = array(), $header = array(), $dataType = 'array')
    {
        \Framework\Libs\Http::$post = $post;
        \Framework\Libs\Http::$request = new \stdClass();
        \Framework\Libs\Http::$request->post = \Framework\Libs\Http::$post;
        $result = \Framework\Bootstrap\Gateway::getCheckParamsErrorInfo();
        if (!empty($result)) {
            return $result;
        }
        $classPath = '\App\Apis\\'.$class;
        $obj = new $classPath();
        $obj->params = \Framework\Libs\Http::$post;
        $data = $obj->{$method}();
        $data = \Framework\Libs\Utility::converToHump($data);
        $data = \Framework\Libs\Utility::getOutputData($data['codeArr'], $data['result'], $data['extData']);
        if ($dataType == 'json') {
            return;
        } else {
            $data = json_decode($data, true);
        }
        return $data;
    }
    
    /**
     * 模拟设置用户ID
     * @param int $memberId
     */
    public function setMemberId($memberId)
    {
        \Framework\Libs\Http::$memberId = $memberId;
    }
}