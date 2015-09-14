<?php
/**
 * 启动服务
 * cli下运行
 * 接收一个参数，表示项目的名称
 * 产生两个常量 FRAMEWORK_PATH，APP_NAME
 */

error_reporting(E_ALL);
ini_set('display_errors','On');

//定义框架路径
define('FRAMEWORK_PATH', dirname(__DIR__));

//定义应用路径
define('APP_PATH', dirname(FRAMEWORK_PATH).'/app');

//启动服务
require_once FRAMEWORK_PATH . '/bootstrap/HttpServer.php';
\Framework\Bootstrap\HttpServer::run();
