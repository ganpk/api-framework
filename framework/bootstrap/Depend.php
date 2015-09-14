<?php
namespace Framework\Bootstrap;

use Monolog\Logger;
use \Core\Libs\LogsHandler;

/**
 * 依赖注入，向IOC容器注入核心依赖的对象
 */
class Depend
{
    /**
     * 注入核心服务
     */
    public static function inject()
    {
        //注入monolog日志对象
        \Framework\Libs\Ioc::bind('logs', function () {
            static $logObj = null;
            if ($logObj == null) {
                $logObj = new Logger('logs');
                $logFile = 'api.log';
                $logObj->pushHandler(new LogsHandler($logFile, Logger::WARNING));
            }
            return $logObj;
        });

    }
}
