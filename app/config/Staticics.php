<?php
namespace App\Config;

/**
 * 上报统计中心配置
 */
class Staticics extends \Framework\Libs\Config
{
    /**
     * 上报地址，为空表示不开启上报
     * @example udp://127.0.0.1:55656
     * @var string
     */
    public $reportAddr = '';
}
