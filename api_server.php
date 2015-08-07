<?php
/**
 * 服务端入口文件
 * 
 * - 操作命令：
 *   - 启动：php apis.php
 * - 启动时执行流程：
 *   - 引入自动加载类
 *   - 注册根命名空间对应的目录关系到自动加载类中
 *   - 启动gateway监听TCP端口
 * - 响应请求流程
 *   - 收到客户端连接和数据
 *   - 认证请求
 *   - 分发请求
 *   - 响应数据
 *   - 断开连接
 */

error_reporting(E_ALL);
ini_set('display_errors','On');

//定义根目录路径
define('ROOT_PATH', __DIR__);

//引入自动加载类
$loader = require './vendor/autoload.php';

//注册根命名空间对应的目录关系到自动加载类中
require './bootstrap/RegistNamespace.php';
Bootstrap\RegistNameSpace::instance(ROOT_PATH,['vendor','documents'],$loader)->register();

//启动服务，对外提供服务
Bootstrap\Server::instance()->start();

