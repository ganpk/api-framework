<?php
namespace Apps\V1\Modules;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * 示例module
 * Class Demo
 * @package Apps\V1\Modules
 */
class Demo extends BaseModule
{
    public function test()
    {
        $log = new Logger('逻辑错误');
        $log->pushHandler(new StreamHandler('php://stdout', Logger::WARNING));
        // add records to the log
        $log->addWarning('Foo',array('A'=>'B','C'=>'D'));
        return array('demo' => 'hello world module v1 test method');
    }
}