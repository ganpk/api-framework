<?php
namespace Bootstrap;

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
     * 是否正在运行中
     * @var boolean
     */
    private static $isRuning = false;
    
    /**
     * tcp server 的实例化对象
     * @var swoole_http_server
     */
    private $serv = null;
    
    /**
     * 单例模式禁止外部实例化
     */
    private final function __construct()
    {
    }

    /**
     * 单例模式禁止外部克隆
     */
    private final function __clone()
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
        if (self::$isRuning) {
            //已经运行了，不需要再运行一次
            return;
        }
        //监听端口
        $this->serv = new \swoole_http_server(\Config\Server::$listen['ip'], \Config\Server::$listen['port']);

        //标记状态为正在运行
        self::$isRuning = true;
        
        //配置sever
        $this->serv->set(\Config\Server::$settings);
        
        //注册请求数据完整后的回调方法
        $this->serv->on('request' , array($this, 'onRequest'));
        
        //TODO；1.平滑重启，2.修改代码自动reload
        $this->serv->on('WorkerStart', array($this, 'onWorkerStart'));
        
        //启动服务器
        $this->serv->start();
    }
    
    /**
     * 请求数据完整后的回调方法
     * @param object swoole_http_request
     * @param object swoole_http_response
     */
    public function onRequest($request, $response)
    {
        //上下文信息保存到Http类中,并转移给gateway网关层处理响应
        new \Bootstrap\Gateway(new \Bootstrap\Http($request, $response));
    }
    
    /**
     * 此事件在worker进程/task进程启动时发生。这里创建的对象可以在进程生命周期内使用。
     * @param swoole_http_server $serv
     * @param int $worker_id worder进程id
     */
    public function onWorkerStart($serv, $worker_id)
    {
        //TODO:是否需要生成重启的shell文件
        //TODO:还要再调整下顺序，包括增加命名空间等全移到这儿，以免重启不生效
        //定义环境运行模式
        \Bootstrap\RunMod::init();
    }
    
    
    
    
}