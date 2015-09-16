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