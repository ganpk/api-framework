<?php
namespace Framework\Bootstrap;

/**
 * HttpServer HTTP服务类
 * Class HttpServer
 * @package Core\Bootstrap
 */
class HttpServer
{
    public static $serverName = '';
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
        //标记运行环境
        require FRAMEWORK_PATH . '/bootstrap/RunMod.php';
        \Framework\Bootstrap\RunMod::init();

        //server配置文件
        $serverConf = require APP_PATH.'/config/Server.php';
        self::$serverName = $serverConf['name'];

        //实例化swoole http server 对象
        $serv = new \swoole_http_server($serverConf['host'], $serverConf['port']);

        //配置sever
        $serv->set($serverConf['swooleSettings']);

        //删除变量，释放内存
        unset($serverConf);

        //注册启动主进程回调
        $serv->on('Start', '\Framework\Bootstrap\HttpServer::onStart');

        //注册worker进程启动回调
        $serv->on('WorkerStart', '\Framework\Bootstrap\HttpServer::onWorkerStart');

        //注册worker进程出错时回调
        $serv->on('WorkerError', '\Framework\Bootstrap\HttpServer::onWorkerError');

        //注册请求数据完整后的回调方法
        $serv->on('Request', '\Framework\Bootstrap\HttpServer::onRequest');

        //注册task回调用
        $serv->on('Task', '\Framework\Bootstrap\HttpServer::onTask');

        //注册Finish回调用
        $serv->on('Finish', '\Framework\Bootstrap\HttpServer::onFinish');

        //启动服务器
        $serv->start();
    }

    /**
     * 启动主进程回调函数
     */
    public static function onStart()
    {
        //设置主进程别名
        $processName = 'swoole_manager_' . self::$serverName;
        swoole_set_process_name($processName);
        //生成重启的shell脚本
        $reload = "echo 'Reloading...'\n";
        $reload .= "pid=$(pidof {$processName})\n";
        $reload .= "kill -USR1 \"\$pid\"\n";
        $reload .= "echo 'Reloaded'\n";
        $fileName = 'reload.sh';
        file_put_contents(FRAMEWORK_PATH . "/bin/{$fileName}", $reload);
        //生成关闭shell脚本
        $shutdown = "echo 'shutdown...'\n";
        $shutdown .= "pid=$(pidof {$processName})\n";
        $shutdown .= "kill -15 \"\$pid\"\n";
        $shutdown .= "echo 'done'\n";
        $fileName = 'shutdown.sh';
        file_put_contents(FRAMEWORK_PATH . "/bin/{$fileName}", $shutdown);
    }

    /**
     * 此事件在worker进程/task进程启动时发生。这里创建的对象可以在进程生命周期内使用。
     * @param swoole_http_server $serv
     * @param int $worker_id worder进程id
     */
    public static function onWorkerStart($serv, $worker_id)
    {
        //设置进程名称
        swoole_set_process_name('swoole_' . self::$serverName . '_' . $worker_id);
        require FRAMEWORK_PATH . '/bootstrap/initWorker.php';
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
//        \Core\Libs\DbManager::connect();

        //刷新http实体类数据
        \Framework\Bootstrap\Http::refreshHttpData($request, $response);

        //调用gateway网关层处理响应
        \Framework\Bootstrap\Gateway::handler();
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