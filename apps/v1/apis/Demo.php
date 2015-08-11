<?php
namespace Apps\V1\apis;

class Demo extends \Apps\V1\apis\BaseApi
{
    public function test()
    {
        return array('demo'=>'test');
    }
}