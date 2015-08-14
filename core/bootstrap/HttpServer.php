<?php
namespace Core\Bootstrap;

/**
 * HttpServer 类
 */
class HttpServer
{
    /**
     * 存放当前实例化类
     * @var Object HandlerNamespace
     */
    private static $instance = null;
    
    /**
     * tcp server 的实例化对象
     * @var swoole_http_server
     */
    private $serv = null;
    
    /**
     * 单例模式禁止外部实例化
     */
    final private function __construct()
    {
    }

    /**
     * 单例模式禁止外部克隆
     */
    final private function __clone()
    {
    }
    
    /**
     * 获取实例化对象
     * @return \Bootstrap\HttpServer
     */
    public static function instance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * 启动TCP网络服务
     */
    public function start() 
    {
        //标记运行环境
        require ROOT_PATH.'/core/bootstrap/RunMod.php';
        \Core\Bootstrap\RunMod::init();

        //获取项目的server配置文件
        $serverConfig = $this->getServerSetting();

        //实例化swoole http server 对象
        $this->serv = new \swoole_http_server($serverConfig['host'], $serverConfig['port']);
        
        //配置sever
        $this->serv->set($serverConfig['settings']);

        //注册启动主进程回调
        $this->serv->on('Start', array($this, 'onStart'));

        //注册worker进程启动回调
        $this->serv->on('WorkerStart', array($this, 'onWorkerStart'));

        //注册worker进程出错时回调
        $this->serv->on('WorkerError', array($this, 'onWorkerError'));

        //注册请求数据完整后的回调方法
        $this->serv->on('Request' , array($this, 'onRequest'));

        //注册task回调用
        $this->serv->on('Task', array($this, 'onTask'));

        //注册Finish回调用
        $this->serv->on('Finish', array($this, 'onFinish'));

        //启动服务器
        $this->serv->start();
    }

    /**
     * 获取项目中server配置文件
     */
    public function getServerSetting()
    {
        //先判断当前环境的
        $configPath = PROJECT_PATH.'/server_'.RUN_MOD.'.php';
        if (!file_exists($configPath)) {
            //当前环境的server配置不存在，则拿默认的server.php
            $configPath = PROJECT_PATH."/server.php";
        }
        $setting = require $configPath;
        return $setting;
    }

    /**
     * 启动主进程回调函数
     */
    public function onStart()
    {
        //生成重启的shell脚本
        $processName = 'swoole_manager_'.PROJECT_NAME;
        swoole_set_process_name($processName);
        $reload  = "echo 'Reloading...'\n";
        $reload .= "pid=$(pidof {$processName})\n";
        $reload .= "kill -USR1 \"\$pid\"\n";
        $reload .= "echo 'Reloaded'\n";
        $fileName = 'reload_'.PROJECT_NAME.'.sh';
        file_put_contents(ROOT_PATH."/core/bin/{$fileName}",$reload);
    }

    /**
     * 此事件在worker进程/task进程启动时发生。这里创建的对象可以在进程生命周期内使用。
     * @param swoole_http_server $serv
     * @param int $worker_id worder进程id
     */
    public function onWorkerStart($serv, $worker_id)
    {
        //设置进程名称
        $processName = 'swoole_'.PROJECT_NAME.'_'.$worker_id;
        swoole_set_process_name($processName);

        //引入自动加载类
        $loader = require ROOT_PATH.'/vendor/autoload.php';

        //注册根命名空间对应的目录关系到自动加载类中
        require ROOT_PATH.'/core/bootstrap/RegistNamespace.php';
        \Core\Bootstrap\RegistNameSpace::instance(ROOT_PATH, ['vendor', 'documents', 'logs'], $loader)->register();
    }
    
    /**
     * 请求数据完整后的回调方法
     * @param object swoole_http_request
     * @param object swoole_http_response
     */
    public function onRequest($request, $response)
    {
        //创建连接数据库资源。
        //TODO:目前是每次都要连接，为了减少连接次数，需要移到onWorkerStart中，也就是一个worker进程使用一个连接，需要在ORM中处理断线重连
        \Libs\DbManager::connect();
        //上下文信息保存到Http类中,并转移给gateway网关层处理响应
        new \Core\Bootstrap\Gateway(new \Core\Bootstrap\Http($request, $response));
    }
    
    /**
     * task回调
     * @param $serv
     * @param $task_id
     * @param $from_id
     * @param $data
     */
    public function onTask($serv, $task_id, $from_id, $data)
    {
    }

    /**
     * task finish 回调
     * @param $serv
     * @param $task_id
     * @param $from_id
     * @param $data
     */
    public function onFinish($serv, $task_id, $data)
    {
    }

    /**
     * 记录worker/task进程出错日志
     * @param $serv
     * @param $worker_id
     * @param $worker_pid
     * @param $exit_code
     */
    public function onWorkerError($serv, $worker_id, $worker_pid, $exit_code)
    {
        //记录日志，用于报警和监控Worker进程是否有异常退出
    }
}