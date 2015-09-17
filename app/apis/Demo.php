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
     *
     * @SWG\Post(
     *     path="/Demo/test",
     *     summary="示例api，test方法",
     *     tags={"Demo"},
     *     description="主要展示了如何获取memberId和获取外部参数和调用service层方法",
     *     @SWG\Parameter(name="id", in="header", description="用户ID", required="true",type="integer"),
     *     @SWG\Parameter(name="productId", in="path", description="商品ID", required="true",type="integer"),
     *     @SWG\Response(
     *          response="200",
     *          description="返回测试数据",
     *          @SWG\Schema(ref="#/definitions/Demo_test")
     *    )
     * )
     */
    public function test()
    {
        $memberId = \Framework\Libs\Http::getMemberId(false);

        //获取需要的参数
        $productId = $this->getParam('productId', 0);
        $result = (new \App\Services\Demo())->test();
        return $this->output(\App\Config\Code::$SUCCESS, $result);
    }

    /**
     * @SWG\Post(
     *     path="/Demo/test2",
     *     summary="示例api，test2方法",
     *     tags={"Demo"},
     *     description="这是示例方法2，主要显示了如何响应数据给客户端",
     *     @SWG\Response(response="200", description="返回测试test2的测试数据")
     * )
     */
    public function test2()
    {
        return $this->output(\App\Config\Code::$SUCCESS, array('name'=>'my name is xiangdong gan'));
    }
}