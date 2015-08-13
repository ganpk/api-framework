<?php
namespace Bootstrap;

/**
 * 制定运行模式
 * 定义运行环境常量 RUN_MOD
 */
class RunMod
{
    /**
     * 初使化运行模式
     */
    public static function init()
    {
        $hostname = gethostname();
        $runMod = '';
        foreach (\Config\Config::$hostnameRule as $k=>$v) {
            if (preg_match($k, $hostname)) {
                //找到了按规则
                $runMod = $v;
                break;
            }
        }
        if ($runMod == '') {
            //没有匹配到规则，则心主机名表示当前RUN_MOD值
            $runMod = $hostname;
        }
        define('RUN_MOD', $runMod);
    }
}