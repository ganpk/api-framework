<?php
namespace Framework\Bootstrap;

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
        //引入配置文件，此时
        $envConfig = require APP_PATH . '/config/Env.php';
        $hostname = gethostname();
        $runMod = '';
        foreach ($envConfig as $k => $v) {
            if (preg_match($k, $hostname)) {
                //配置到了主机
                $runMod = $v;
                break;
            }
        }
        if ($runMod == '') {
            //没有匹配到主机，则默认以主机名表示当前RUN_MOD值
            $runMod = $hostname;
        }
        define('RUN_MOD', $runMod);
    }
}