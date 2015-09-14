<?php
/**
 * WORKER 进程初使化脚本
 */

//注册根命名空间对应的目录关系到自动加载类中
require FRAMEWORK_PATH . '/bootstrap/RegistNamespace.php';
(new \Core\Bootstrap\RegistNameSpace())->register();

//注入核心服务
\Framework\Bootstrap\Depend::inject();

//将code的注释刷为msg
\Framework\Libs\Utility::refreshCodeAnnotation();