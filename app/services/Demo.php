<?php
namespace App\Services;

/**
 * module
 * Class Demo
 * @package App\Services;
 */
class Demo extends \App\Services\BaseService
{
    /**
     * 调试方法
     * @return array
     */
    public function test($productId,$crowdFundStatus)
    {
        //获取model数据
//        $memberInfo = \App\Models\Member::find(945)->toArray();
//        return $memberInfo;

        if($productId == '5'){
            throw (new \App\Exceptions\Demo())->setResponseCodeInfo(\App\Config\Code::$SYSTEM_BUSY);
        }
        
        return array('user_id' => 1, 'user_email' => 'phpwww@126.com', 'my_cart' => array('cart_monty' => '22', 'cart_weight' => '22kg'));
    }
}