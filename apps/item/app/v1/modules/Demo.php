<?php
namespace Apps\Item\App\V1\Modules;

/**
 * 示例module
 * Class Demo
 * @package Apps\V1\Modules
 */
class Demo extends BaseModule
{
    public function test()
    {
        return array('demo' => 'hello world module v1 test method');
    }
}