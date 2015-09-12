<?php
/**
 * 模块服务配置
 */

/*
 * 配置说明
 *
 * server部分
 * 详情参考swoole文档：http://wiki.swoole.com/wiki/page/281.html
 * host：监听IP,0.0.0.0表示监听所有地址
 * port：监听端口号，监听小于1024端口需要root权限。建议9500-9600之间
 * settings：服务选项
 *      daemonize：守护进程化。设置daemonize => 1时，程序将转入后台作为守护进程运行。长时间运行的服务器端程序必须启用此项
 *      worker_num：设置启动的worker进程数。
 *                  业务代码是阻塞的，需要设置一个合理值，比如1个请求耗时100ms，要提供1000QPS的处理能力，那必须配置100个进程或更多。
 *                  每个进程占用40M内存，那100个进程就需要占用4G内存
 *      reactor_num：reactor线程数，reactor_num => 2，通过此参数来调节主进程内事件处理线程的数量，以充分利用多核。默认会启用CPU核数相同的数量。
 *                   reactor_num一般设置为CPU核数的1-4倍，在swoole中reactor_num最大不得超过CPU核数*4。
 *                   考虑到操作系统调度存在一定程度的偏差，可以设置为CPU核数*2，以便最大化利用CPU的每一个核。
 *                   reactor_num必须小于或等于worker_num。如果设置的reactor_num大于worker_num，那么swoole会自动调整使reactor_num等于worker_num
 *      max_request：设置worker进程的最大任务数。一个worker进程在处理完超过此数值的任务后将自动退出。这个参数是为了防止PHP进程内存溢出。
 *                   如果不希望进程自动退出可以设置为0
 *                   当worker进程内发生致命错误或者人工执行exit时，进程会自动退出。主进程会重新启动一个新的worker进程来处理任务
 *      discard_timeout_request：swoole在配置dispatch_mode=1或3后，系统无法保证onConnect/onReceive/onClose的顺序，因此可能会有一些请求数据在连接关闭后，才能到达Worker进程。
 *                               discard_timeout_request配置默认为true，表示如果worker进程收到了已关闭连接的数据请求，将自动丢弃。
 *                               discard_timeout_request如果设置为false，表示无论连接是否关闭Worker进程都会处理数据请求。
 *      dispatch_mode： worker进程如何与reactor进程通信，Swoole提供了3种方式。通过swoole_server_set参数中修改dispatch_mode的值来配置。
 *                      轮询模式：dispatch_mode = 1
 *                      收到的请求数据包会轮询发到每个Worker进程。
 *                      FD取模：dispatch_mode = 2(默认）
 *                      数据包根据fd的值%worker_num来分配，这个模式可以保证一个TCP客户端连接发送的数据总是会被分配给同一个worker进程。 这种模式可能会存在性能问题，作为SOA服务器时，不应当使用此模式。因为客户端很可能用了连接池，客户端100个进程复用10个连接，也就是同时只有10个swoole worker进程在处理请求。这种模式的业务系统可以使用dispatch_mode = 3，抢占式分配。
 *                      Queue模式：dispatch_mode = 3
 *                      此模式下，网络请求的处理是抢占式的，这可以保证总是最空闲的worker进程才会拿到请求去处理。 这个模式的缺点是，客户端连接对应的worker是随机的。不确定哪个worker会处理请求。无法保存连接状态。 当然也可以借助第三方库来实现保存连接状态和会话内容，比如apc/redis/memcache。
 */

$apps = [
    /**
     * item 模块
     */
    'item' => [
        'server' => [
            'host' => '0.0.0.0',
            'port' => '9501',
            'settings' => [
                'daemonize' => 0,
                'worker_num' => 1,
                'reactor_num' => 1,
                'max_request' => 1500,
                'discard_timeout_request' => true,
                'dispatch_mode' => 3,
            ]
        ],
    ],
];

return $apps;