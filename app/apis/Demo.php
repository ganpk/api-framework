<?php
namespace App\Apis;

use Libs;

/**
 * API
 * Class Demo
 * @package App\Apis
 */
class Demo extends \Framework\Libs\Api
{
    /**
     * 调试方法
     * @return array
     */
    public function test()
    {
        $memberId = \Framework\Libs\Http::getMemberId(false);

        //获取需要的参数
        $productId = $this->getParam('productId', 0);



//        $result = \Core\Libs\AppFactory::module('Demo')->test();
//        $result = \App\Config\Db::instance()->mysql;
        $result = (new \App\Services\Demo())->test();
        return $this->output(\App\Config\Code::$SUCCESS, $result);
    }
}