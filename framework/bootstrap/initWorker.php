<?php
/**
 * WORKER 进程初使化脚本
 */

//非生产环境打开错误输出
if(!defined('RUN_MOD')) {
    require FRAMEWORK_PATH . '/bootstrap/RunMod.php';
    \Framework\Bootstrap\RunMod::init();
}
if (RUN_MOD != 'produce') {
    error_reporting(E_ALL);
    ini_set('display_errors','On');
} else {
    error_reporting(0);
}

//注册根命名空间对应的目录关系到自动加载类中
require FRAMEWORK_PATH . '/bootstrap/RegistNamespace.php';
(new \Framework\Bootstrap\RegistNameSpace())->register();

//注入核心服务
\Framework\Bootstrap\Depend::inject();

//将code的注释刷为msg
\Framework\Libs\Utility::refreshCodeAnnotation();
