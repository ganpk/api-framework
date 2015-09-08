<?php
namespace Core\Libs;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

/**
 * 日志处理器
 * Class LogsHandler
 * @package Core\Libs
 */
class LogsHandler extends AbstractProcessingHandler
{
    /**
     * 日志文件名称
     * @var string
     */
    private $logFile = '';

    /**构造方法
     * @param int $logFile 日志文件名称
     * @param bool|int $level
     * @param bool|true $bubble
     */
    public function __construct($logFile, $level = Logger::DEBUG, $bubble = true)
    {
        $this->logFile = $logFile;
        parent::__construct($level, $bubble);
        $this->setLevel($level);
        $this->bubble = $bubble;
    }

    /**
     * 实现写方法
     * @param array $record
     */
    protected function write(array $record)
    {
        //构建日志路径，按日期分目录
        $filePathDir = ROOT_PATH . '/storage/logs/' . date('Y-m-d');
        if (!file_exists($filePathDir)) {
            @mkdir($filePathDir, 0777, true);
        }
        $filePath = $filePathDir . '/' . $this->logFile;
        //异步写文件
        swoole_async_write($filePath, (string)$record['formatted']);
        //TODO:定制错误级别自动发送邮件
    }
}