<?php
namespace Config;

/**
 * 全局配置文件
 */
class Config
{
    /**
     * 是否开启原始的post请求，也就是php://input功能
     * 注意，如果启用了，则在http请求头中Content-type不能等于form格式类型：application/x-www-form-urlencoded，否则会出现post值为空的情况
     * @var bool
     */
    public static $isOpenOriginalPostInput = false;

    /**
     * 优先从header某key中获取真实IP,根据实际情况来，如X-Real-IP，如果没有取到则会取server中的remote_addr
     * 为空表示不从header中拿，直接取server中的remote_addr
     * @var string
     */
    public static $realRemoteAddrHeaderKey = 'X-Real-IP';

}