<?php
//存放目录
if (!empty($argv[1])) {
    $dir = $argv[1];
    if (!is_dir($dir)) {
        exit('not found the directory : '.$dir.PHP_EOL);
    }
} else {
    $dir = FRAMEWORK_PATH.'/bin';
}

//文件名
if (!empty($argv[2])) {
    $fileName = $argv[2];
} else {
    $fileName = 'documents.html';
}
$filePath = "{$dir}/{$fileName}";

//定义框架路径
define('FRAMEWORK_PATH', dirname(__DIR__));
//定义应用路径
define('APP_PATH', dirname(FRAMEWORK_PATH).'/app');
require(FRAMEWORK_PATH."/vendor/autoload.php");
$apiPath = dirname(dirname(__DIR__)).'/app/apis';
$swagger = \Swagger\scan($apiPath);

$res = file_put_contents($filePath,$swagger);

if ($res) {
    exit("【success】{$filePath}".PHP_EOL);
} else {
    exit("【fail】 please check the directory's permission".PHP_EOL);
}

