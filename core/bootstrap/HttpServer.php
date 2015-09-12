<?php
namespace Core\Bootstrap;

/**
 * HttpServer HTTP服务类
 * Class HttpServer
 * @package Core\Bootstrap
 */
class HttpServer
{
    /**
     * 禁止外部实例化
     */
    final private function __construct()
    {
    }

    /**
     * 启动TCP网络服务
     */
    public static function run()
    {
        //获取app的配置文件
        $appConf = self::getAppConf();

        //实例化swoole http server 对象
        $serv = new \swoole_http_server($appConf['server']['host'], $appConf['server']['port']);

        //配置sever
        $serv->set($appConf['server']['settings']);

        //注册启动主进程回调
        $serv->on('Start', '\Core\Bootstrap\HttpServer::onStart');

        //注册worker进程启动回调
        $serv->on('WorkerStart', '\Core\Bootstrap\HttpServer::onWorkerStart');

        //注册worker进程出错时回调
        $serv->on('WorkerError', '\Core\Bootstrap\HttpServer::onWorkerError');

        //注册请求数据完整后的回调方法
        $serv->on('Request', '\Core\Bootstrap\HttpServer::onRequest');

        //注册task回调用
        $serv->on('Task', '\Core\Bootstrap\HttpServer::onTask');

        //注册Finish回调用
        $serv->on('Finish', '\Core\Bootstrap\HttpServer::onFinish');

        //启动服务器
        $serv->start();
    }

    /**
     * 获取app的配置文件
     */
    public static function getAppConf()
    {
        //获取apps配置文件
        $apps = require FRAMEWORK_PATH . '/apps.php';
        if (empty($apps[APP_NAME])) {
            exit('没有发现' . APP_NAME . '项目');
        }
        $appCinfig = $apps[APP_NAME];
        return $appCinfig;
    }

    /**
     * 启动主进程回调函数
     */
    public static function onStart()
    {
        //生成重启的shell脚本
        $processName = 'swoole_manager_' . APP_NAME;
        swoole_set_process_name($processName);
        $reload = "echo 'Reloading...'\n";
        $reload .= "pid=$(pidof {$processName})\n";
        $reload .= "kill -USR1 \"\$pid\"\n";
        $reload .= "echo 'Reloaded'\n";
        $fileName = 'reload_' . APP_NAME . '.sh';
        file_put_contents(FRAMEWORK_PATH . "/bin/{$fileName}", $reload);
    }

    /**
     * 此事件在worker进程/task进程启动时发生。这里创建的对象可以在进程生命周期内使用。
     * @param swoole_http_server $serv
     * @param int $worker_id worder进程id
     */
    public static function onWorkerStart($serv, $worker_id)
    {
        //设置进程名称
        $processName = 'swoole_' . APP_NAME . '_' . $worker_id;
        swoole_set_process_name($processName);

        require FRAMEWORK_PATH . '/core/bootstrap/initWorker.php';
    }

    /**
     * 请求数据完整后的回调方法
     * @param object swoole_http_request
     * @param object swoole_http_response
     */
    public static function onRequest($request, $response)
    {
        //创建连接数据库资源。
        //TODO:目前是每次都要连接，为了减少连接次数，需要移到onWorkerStart中，也就是一个worker进程使用一个连接，需要在ORM中处理断线重连
        //重新连接数据库
        \Core\Libs\DbManager::connect();
        //调用gateway网关层处理响应
        \Core\Bootstrap\Gateway::handler();
        return;
    }

    /**
     * task回调
     * @param $serv
     * @param $task_id
     * @param $from_id
     * @param $data
     */
    public static function onTask($serv, $task_id, $from_id, $data)
    {
    }

    /**
     * task finish 回调
     * @param $serv
     * @param $task_id
     * @param $from_id
     * @param $data
     */
    public static function onFinish($serv, $task_id, $data)
    {
    }

    /**
     * 记录worker/task进程出错日志
     * @param $serv
     * @param $worker_id
     * @param $worker_pid
     * @param $exit_code
     */
    public static function onWorkerError($serv, $worker_id, $worker_pid, $exit_code)
    {
        //记录日志，用于报警和监控Worker进程是否有异常退出
    }

}