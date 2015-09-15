<?php
require '../../framework/libs/Test.php';

/**
 * 测试service的demo类
 * Class testDemo
 */
class testDemo extends \Framework\Libs\Test
{
    /**
     * 测试调用用的test方法
     */
    public function testTest()
    {
        $result = (new \App\Services\Demo())->test();
        $this->assertEquals(3, count($result));
    }
}