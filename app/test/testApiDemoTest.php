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

        $result = \App\Apis\Demo::instance()->test();

        //不为空
        $this->assertNotEmpty($result);
        
        print_r($result);
    }
}