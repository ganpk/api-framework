<?php
/**
 * WORKER 进程初使化脚本
 */

//注册根命名空间对应的目录关系到自动加载类中
require FRAMEWORK_PATH . '/bootstrap/RegistNamespace.php';
(new \Framework\Bootstrap\RegistNameSpace())->register();

//标记运行的环境
\Framework\Bootstrap\RunMod::init();

//注入核心服务
\Framework\Bootstrap\Depend::inject();

//将code的注释刷为msg
\Framework\Libs\Utility::refreshCodeAnnotation();
