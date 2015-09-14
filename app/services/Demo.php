<?php
namespace App\Services;

/**
 * module
 * Class Demo
 * @package App\Services;
 */
class Demo extends \Framework\Libs\Service
{
    /**
     * 调试方法
     * @return array
     */
    public function test()
    {
        return array('user_id' => 1, 'user_email' => 'phpwww@126.com', 'my_cart' => array('cart_monty' => '22', 'cart_weight' => '22kg'));
    }
}