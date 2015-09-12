<?php
/**
 * WORKER 进程初使化脚本
 */

//引入自动加载类
$loader = require FRAMEWORK_PATH . '/vendor/autoload.php';

//注册根命名空间对应的目录关系到自动加载类中
require FRAMEWORK_PATH . '/core/bootstrap/RegistNamespace.php';
\Core\Bootstrap\RegistNameSpace::instance(FRAMEWORK_PATH, ['vendor', 'documents', 'storage', 'bin'], $loader)->register();

//注入核心服务
\Core\Bootstrap\Depend::inject();