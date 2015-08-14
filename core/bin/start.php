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

class t
{
    protected $instance = null;

    /**
     * @return t
     */
    public static function instance()
    {
        $obj = new self();
        print_r(get_object_vars($obj));
        unset($obj->a);
        return $obj;
    }

    public function __get($k){
        echo '11111111';
    }
}

$t = t::instance();
echo $t->a;
exit;

error_reporting(E_ALL);
ini_set('display_errors','On');

//获取参数
$project = empty($argv[1]) ? '' : trim($argv[1]);

//获取存在的模块名
$appsPath = '../../apps';
$projects = array();
$fd = opendir($appsPath);
while (($fname = readdir($fd))) {
    if ($fname == '.' || $fname == '..' || !is_dir($appsPath."/{$fname}")) {
        continue;
    }
    $projects[] = $fname;
}

//检查模块是否存在
if (!in_array($project, $projects)) {
    exit("启动失败，没有发现{$project}项目");
}

//定义根目录路径
define('ROOT_PATH', substr(__DIR__,0,-9));

//定义项目名称
define('PROJECT_NAME', $project);

//定义项目路径
define('PROJECT_PATH', ROOT_PATH.'/apps/'.$project);

//启动HTTP SERVER服务
require ROOT_PATH.'/core/bootstrap/HttpServer.php';
\Core\Bootstrap\HttpServer::instance()->start();
