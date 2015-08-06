<?php
namespace Config;

/**
 * 服务端口层配置文件
 *
 */
class Server
{
    /**
     * tcp 监听端口号
     * @var integer
     */
    public static $LISTEN = 80;
    
    /**
     * worker 进程数，设置为CPU的1-4倍最合理
     * @var integer
     */
    public static $WORKER_NUM = 4;
    
    /**
     * reactor 线程数,,默认会启用CPU核数相同的数量,一般设置为CPU核数的1-4倍,可以设置为CPU核数*2，不能大于workerNum
     * @var integer
     */
    public static $REACTOR_NUM = 4;
    
    /**
     * 设置worker进程的最大任务数。一个worker进程在处理完超过此数值的任务后将自动退出。这个参数是为了防止PHP进程内存溢出。
     * 如果不希望进程自动退出可以设置为0
     * @var integer
     */
    public static $MAX_REQUEST = 1500;
    
    /**
     * 最大允许的连接数，如max_conn => 10000, 此参数用来设置Server最大允许维持多少个tcp连接。超过此数量后，新进入的连接将被拒绝。
     * max_connection最大不得超过操作系统ulimit -n的值，否则会报一条警告信息，并重置为ulimit -n的值
     * max_connection默认值为ulimit -n的值
     * @var integer
     */
    public static $MAX_CONN = 10000;
    
    /**
     * 配置task进程的数量，配置此参数后将会启用task功能。
     * 所以swoole_server务必要注册onTask/onFinish2个事件回调函数。如果没有注册，服务器程序将无法启动。
     * @var integer
     */
    //public static $TASK_WORKER_NUM = 10000;
    
    /**
     * 设置task进程与worker进程之间通信的方式。
     * 所以swoole_server务必要注册onTask/onFinish2个事件回调函数。如果没有注册，服务器程序将无法启动。
     * @var integer 1, 使用unix socket通信 2, 使用消息队列通信 3, 使用消息队列通信，并设置为争抢模式,设置为3后，task/taskwait将无法指定目标进程ID
     */
    //public static $TASK_IPC_MODE = 3;
    
    /**
     * 设置task进程的最大任务数。一个task进程在处理完超过此数值的任务后将自动退出。
     * 这个参数是为了防止PHP进程内存溢出。如果不希望进程自动退出可以设置为0。
     * task_max_request默认为5000，受swoole_config.h的SW_MAX_REQUEST宏控制1.7.17以上版本默认值调整为0，不会主动退出进程
     * @var integer
     */
    //public static $TASK_MAX_REQUEST = 0;
    
}