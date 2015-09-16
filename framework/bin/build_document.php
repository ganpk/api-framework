<?php
//定义框架路径
define('FRAMEWORK_PATH', dirname(__DIR__));
//定义应用路径
define('APP_PATH', dirname(FRAMEWORK_PATH).'/app');
require(FRAMEWORK_PATH."/vendor/autoload.php");
$apiPath = dirname(dirname(__DIR__)).'/app/apis';
$swagger = \Swagger\scan($apiPath);
file_put_contents('documents.html',$swagger);

