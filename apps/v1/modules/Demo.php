<?php
namespace Apps\V1\Modules;

/**
 * Ê¾Àý module
 * Class Demo
 * @package Apps\V1\Modules
 */
class Demo extends \Apps\V1\Modules\BaseModule
{
    public function test()
    {
        return array('demo' => 'hello world module');
    }
}