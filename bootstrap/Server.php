<?php
namespace Bootstrap;

/**
 * TCP服务类
 * @author gxd
 *
 */
class Server
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
     */
    public static function instance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * 启动网关服务
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
        //上下文信息保存到HttpManager类中,并转移给gateway网关层处理响应
        new \Bootstrap\Gateway(new \Bootstrap\Http($request, $response));
    }
    
    
    
    
}