<?php
/**
 * 服务端入口文件
 * - 操作命令：
 *   - 启动：php api_server.php
 *   - 停止：
 *   - 重启：
 */

error_reporting(E_ALL);
ini_set('display_errors','On');

//定义根目录路径
define('ROOT_PATH', __DIR__);

//启动HTTP SERVER服务
require ROOT_PATH.'/bootstrap/HttpServer.php';
\Bootstrap\HttpServer::instance()->start();

