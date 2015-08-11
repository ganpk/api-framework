<?php
namespace Apps\V2\Apis;

class Products
{
    public function test($param){
        return array('response'=>'v2','params'=>$param);
    }
}