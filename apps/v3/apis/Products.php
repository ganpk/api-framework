<?php
namespace Apps\V3\Apis;

class Products
{
    public function test(){
        echo 'test v3'.PHP_EOL;
        \Lib\AppFactory::module('Products');
        return array('test'=>'hello world v3');
    }
}