<?php
/**
 * 服务启动脚本
 * cli下运行
 * 接收一个参数，表示项目的名称，apps目录下的子目录全部为项目名称
 * 产生常量：
 *      ROOT_PATH 根目录路径
 *      PROJECT_PATH 项目路径
 * 开启服务
 */
//获取参数
$app = empty($argv[1]) ? '' : trim($argv[1]);

//获取apps配置信息
{
    exit(__DIR__);
    $frameworkePath = rtrim(__DIR__,'');
    $apps = require __DIR__.'';
    if ($app == '' || empty($apps[$app])) {
        exit("启动失败，没有发现{$app}项目");
    }
}

//获取存在的模块
//$appsPath = $binDir . '/../../apps';
//$projects = array();
//$fd = opendir($appsPath);
//while (($fname = readdir($fd))) {
//    if ($fname == '.' || $fname == '..' || !is_dir($appsPath . "/{$fname}")) {
//        continue;
//    }
//    $projects[] = $fname;
//}
//
////检查模块是否存在
//if (!in_array($project, $projects)) {
//    exit("启动失败，没有发现{$project}项目");
//}
//
////定义根目录路径
//define('ROOT_PATH', substr(__DIR__, 0, -9));
//
////定义项目名称
//define('PROJECT_NAME', $project);
//
////定义项目路径
//define('PROJECT_PATH', ROOT_PATH . '/apps/' . $project);

//启动HTTP SERVER服务
//require ROOT_PATH . '/core/bootstrap/HttpServer.php';
//\Core\Bootstrap\HttpServer::instance()->start();
