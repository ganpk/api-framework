<?php
namespace Framework\Bootstrap;

use Monolog\Logger;
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
                $logObj = new \Monolog\Logger('logs');
                $logFile = APP_PATH.'/storage/logs/default.log';
                //按天记录debug日志
                $rotatingHandler = new \Monolog\Handler\RotatingFileHandler($logFile, 30, Logger::DEBUG);
                $logObj->pushHandler($rotatingHandler);
            }
            return $logObj;
        });

    }
}
