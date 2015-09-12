<?php
/**
 * 启动服务
 * cli下运行
 * 接收一个参数，表示项目的名称
 * 产生两个常量 FRAMEWORK_PATH，APP_NAME
 */

error_reporting(E_ALL);
ini_set('display_errors','On');

{
    //获取参数
    $app = empty($argv[1]) ? '' : trim($argv[1]);

    if ($app == '') {
        exit("启动失败，请指定启动哪一个模块");
    }

    //定义框架路径常量FRAMEWORK_PATH
    $frameworkePath = rtrim(__DIR__, '/bin');
    define('FRAMEWORK_PATH', $frameworkePath);

    //TODO:最好检查下app配置的项目目录是否存在

    //定义项目名称
    define('APP_NAME', $app);
}

//启动服务
require_once FRAMEWORK_PATH . '/core/bootstrap/HttpServer.php';
\Core\Bootstrap\HttpServer::run();
