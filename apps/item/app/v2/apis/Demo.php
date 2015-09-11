<?php
namespace Apps\Item\App\V2\Apis;

use Libs;

/**
 * API
 * Class Demo
 * @package Apps\V1\Apis
 */
class Demo extends \Apps\Item\App\V1\Apis\Demo
{
    /**
     * 测试方法
     * @param $params
     */
    public function test2()
    {
        //获取需要的参数
        //$productId = \Libs\CheckParam::instance($params)->isRequire(true)->check('productId');
        //$memberId = \Libs\CheckParam::instance($params)->defaultValue(0)->check('memberId');

        //获取model数据 //获取model数据
        $result = array('hello world v2 test mehtod');
        return $this->output(\Config\Code::$SUCCESS, $result);
    }
}