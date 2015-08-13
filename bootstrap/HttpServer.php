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
        //监听端口
        require ROOT_PATH.'/config/Server.php';
        $this->serv = new \swoole_http_server(\Config\Server::$listen['ip'], \Config\Server::$listen['port']);
        
        //配置sever
        $this->serv->set(\Config\Server::$settings);

        //注册worker进程启动回调
        $this->serv->on('WorkerStart', array($this, 'onWorkerStart'));

        //注册请求数据完整后的回调方法
        $this->serv->on('request' , array($this, 'onRequest'));

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
        //创建连接数据库资源。
        //TODO:目前是每次都要连接，为了减少连接次数，需要移到onWorkerStart中，也就是一个worker进程使用一个连接，需要在ORM中处理断线重连
        \Libs\DbManager::connect();
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
        //引入自动加载类
        $loader = require './vendor/autoload.php';

        //注册根命名空间对应的目录关系到自动加载类中
        require './bootstrap/RegistNamespace.php';
        \Bootstrap\RegistNameSpace::instance(ROOT_PATH,['vendor','documents'],$loader)->register();

        //定义环境运行模式
        \Bootstrap\RunMod::init();
    }
}