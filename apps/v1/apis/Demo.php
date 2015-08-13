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
    public static $is = 0;
    public function test($params)
    {
        $productId = \Libs\CheckParam::instance($params)->isRequire(true)->check('productId');
        $memberId = \Libs\CheckParam::instance($params)->defaultValue(0)->check('memberId');
        echo '-----------------' . PHP_EOL;
        if (self::$is == 0) {
            $result = \Apps\V1\Models\Member::find(1)->toArray();
            self::$is = 1;
        } else {
            $result = \Apps\V1\Models\Member::whereRaw('ids > ?', array(1))->get()->toArray();
        }
    }
}