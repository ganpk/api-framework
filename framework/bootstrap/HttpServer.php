<?php
namespace Framework\Bootstrap;

/**
 * HttpServer HTTP服务类
 * Class HttpServer
 * @package Core\Bootstrap
 */
class HttpServer
{
    public $nowTime = '';
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
        //实例化swoole http server 对象
        $serv = new \swoole_http_server(\App\Config\Server::$host, \App\Config\Server::$port);

        //配置sever
        $serv->set(\App\Config\Server::$swooleSettings);

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
        $processName = 'swoole_manager_' . \App\Config\Server::$name;
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
        swoole_set_process_name('swoole_' . \App\Config\Server::$name . '_' . $worker_id);
        require FRAMEWORK_PATH . '/bootstrap/InitWorker.php';
    }

    /**
     * 请求数据完整后的回调方法
     * @param object swoole_http_request
     * @param object swoole_http_response
     */
    public static function onRequest($request, $response)
    {
        //刷新http实体类数据
        \Framework\Libs\Http::refreshHttpData($request, $response);

        //添加API上报统计中心
        if (!empty(\App\Config\Staticics::$reportAddr)) {
            self::addApiStatistic();
        }
        
        //调用gateway网关层
        $resContent = \Framework\Bootstrap\Gateway::handler();
        
        //响应数据
        self::response($response, $resContent);
        
        //上报统计中心
        self::reportStatistic($resContent);
        
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
    
    /**
     * 响应
     * @param unknown $response 响应对象
     * @param string $content 响应内容
     */
    public static function response($response, $content = '')
    {
        $response->status = 200;
        $response->header('Content-Type','application/json;charset=UTF-8');
        $response->end($content);
    }
    
    /**
     * 添加一条API上报
     */
    public static function addApiStatistic()
    {
        //加入上报列表
        \Framework\Libs\StatisticClient::tick(\App\Config\Server::$name, \Framework\Libs\Http::$statisticApiName);
    }
    
    /**
     * 上报统计中心
     * @param unknown $content
     */
    public static function reportStatistic($content)
    {
        if (empty(\App\Config\Staticics::$reportAddr)) { //未开启上报功能
            return;
        }
        //组装上报信息
        $resArr = json_decode($content, true);
        if (!isset($resArr['code']) || !isset($resArr['msg'])) {
            $resArr = \App\Config\Code::$ELLEGAL_RESPONSE_CONTENT;
            $msg = empty($content) ? '[空]' : $content;
            $resArr['msg'] = sprintf($resArr['msg'], $msg);
        }
        $isSuccess = $resArr['code'] == '0' ? true : false;
        $resArr['code'] = $resArr['code'] == '0' ? '1' : $resArr['code']; //将code为0转换为1，否则统计曲线会显示不出来
        $reportAddress = 'udp://127.0.0.1:55656';
        
        //开始上报
        \Framework\Libs\StatisticClient::report(\App\Config\Server::$name, \Framework\Libs\Http::$statisticApiName, $isSuccess, $resArr['code'], $resArr['msg'], $reportAddress);
    }

}