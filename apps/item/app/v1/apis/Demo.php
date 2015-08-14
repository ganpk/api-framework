<?php
namespace Apps\V1\Apis;

use Libs;
/**
 * API
 * Class Demo
 * @package Apps\V1\Apis
 */
class Demo extends BaseApi
{
    /**
     * 测试方法
     * @param $params
     */
    public function test()
    {
        //获取需要的参数
       $productId = $this->getParam('productId',0);

        //获取model数据
//        $memberModel = \Libs\AppFactory::model('Member');
//        $result = $memberModel::find(1)->toArray();
        $result = \Libs\AppFactory::module('Demo')->test();
        return $result;
    }
}