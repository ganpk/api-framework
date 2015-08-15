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
        return array('user_id' => 1,'user_email' => 'phpwww@126.com','my_cart' => array('cart_monty'=>'22','cart_weight'=>'22kg'));
    }
}