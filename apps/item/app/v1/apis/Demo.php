<?php
namespace Apps\Item\App\V1\Apis;

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
     * @return array
     */
    public function test()
    {
        //获取需要的参数
        $productId = $this->getParam('productId', 0);

        //获取model数据
        $memberModel = \Core\Libs\AppFactory::model('Member');
        $result = $memberModel::find(1)->toArray();
        $result = \Core\Libs\AppFactory::module('Demo')->test();
        return $this->output(\Config\Code::$SUCCESS, $result);
    }
}