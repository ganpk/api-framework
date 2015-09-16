<?php
require '../../framework/libs/Test.php';

/**
 * 测试api demo中的test方法
 */
class testApiDemoTest extends \Framework\Libs\Test
{
    public function testTest()
    {
        //模拟数据
        \Framework\Libs\Http::$memberId = 945;

        $result = \App\Apis\Demo::instance()->test();

        print_r($result);
    }
}