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
        $envConfig = \App\Config\Env::$rule;
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
            //没有匹配到主机，则默认当前环境为dev
            $runMod = 'dev';
        }
        define('RUN_MOD', $runMod);

        self::displayErrors();
    }

    /**
     * 根据环境决定是否打php错误信息
     */
    public static function displayErrors()
    {
        if (RUN_MOD != 'produce') {
            //非生产环境打开错误输出
            error_reporting(E_ALL);
            ini_set('display_errors','On');
        } else {
            //生产环境不显示错误信息
            error_reporting(0);
        }
    }

}