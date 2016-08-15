<?php
require './BaseTest.php';

/**
 * 测试api demo中的test方法
 */
class testApiDemoTest extends \App\Test\BaseTest
{
    public function testTest()
    {
        //模拟数据
        \Framework\Libs\Http::$memberId = 945;

        $params = [
            'productId'=>5,
            'crowdFundStatus' => 'funding'
        ];
        $result = $this->callApi('Demo', 'test', $params);
        //不为空
        $this->assertNotEmpty($result);
        
        print_r($result);
    }
}