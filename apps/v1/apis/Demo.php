<?php
namespace Apps\V1\Apis;

/**
 * ʾ��API
 * Class Demo
 * @package Apps\V1\Apis
 */
class Demo extends \Apps\V1\Apis\BaseApi
{
    public function test()
    {
        $result = \Libs\AppFactory::module('Demo')->test();
        return array('demo'=>'test',array('module'=>$result));
    }
}