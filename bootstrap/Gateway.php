<?php
namespace Bootstrap;

/**
 * 应用入口网关类，处理网络层服务
 * @author gxd
 *
 */
class Gateway
{
    /**
     * 启动网关服务
     */
    public static function start() 
    {
        $serv = new swoole_http_server("127.0.0.1", 9502);
        
        $serv->on('Request', function($request, $response) {
            var_dump($request->get);
            var_dump($request->post);
            var_dump($request->cookie);
            var_dump($request->files);
            var_dump($request->header);
            var_dump($request->server);
        
            $response->cookie("User", "Swoole");
            $response->header("X-Server", "Swoole");
            $response->end("<h1>Hello Swoole!</h1>");
        });
        
            $serv->start();
    }
}