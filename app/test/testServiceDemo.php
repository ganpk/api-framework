<?php
require './BaseTest.php';

/**
 * 测试service的demo类
 * Class testDemo
 */
class testDemo extends \App\Test\BaseTest
{
    /**
     * 测试调用用的test方法
     */
    public function testTest()
    {
        //测试数据库
//        $result = \App\Models\Member::find(945);
//        $this->assertNotNull($result);
    }

    public function testTest2()
    {
        //标记尚为完成
        $this->markTestIncomplete();
    }


}