<?php
/**
 * 测试入口脚本
 */

error_reporting(E_ALL);
ini_set('display_errors','On');

//定义根目录路径
define('ROOT_PATH', __DIR__);

//标记运行环境
require ROOT_PATH.'/core/bootstrap/RunMod.php';
\Core\Bootstrap\RunMod::init();

//获取项目的server配置文件
//先判断当前环境的
$configPath = PROJECT_PATH.'/server_'.RUN_MOD.'.php';
if (!file_exists($configPath)) {
    //当前环境的server配置不存在，则拿默认的server.php
    $configPath = PROJECT_PATH."/server.php";
}
$setting = require $configPath;

//引入自动加载类
$loader = require ROOT_PATH.'/vendor/autoload.php';

//注册根命名空间对应的目录关系到自动加载类中
require ROOT_PATH.'/core/bootstrap/RegistNamespace.php';
\Core\Bootstrap\RegistNameSpace::instance(ROOT_PATH, ['vendor', 'documents', 'logs'], $loader)->register();

//注入核心服务
\Core\Bootstrap\Depend::inject();

//设置数据库连接
\Core\Libs\DbManager::connect();