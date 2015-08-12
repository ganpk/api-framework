<?php
namespace Apps\V1\Apis;

use Libs;
/**
 * API
 * Class Demo
 * @package Apps\V1\Apis
 */
class Demo extends \Apps\V1\Apis\BaseApi
{
    public function test($params)
    {
    	$productId = \Libs\CheckParam::instance($params)->isRequire(true)->check('productId');
    	$memberId = \Libs\CheckParam::instance($params)->isRequire(false)->defaultValue(0)->check('memberId');
    	return $productId."-------".$memberId;
        $result = \Libs\AppFactory::module('Demo')->test();
        return array('demo'=>'test',array('module'=>$result));
    }
}