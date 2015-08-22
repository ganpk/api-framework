<?php
/**
 * 测试demo脚本
 */

define('PROJECT_PATH',substr(__DIR__,0,-5));
define('PROJECT_NAME',ltrim(strrchr(PROJECT_PATH,'/'),'/'));
require '../../../test.php';
class testDemo extends PHPUnit_Framework_TestCase
{
    public function testTest()
    {
        $stack = array(1);
        $this->assertEquals(0, count($stack));
    }
}