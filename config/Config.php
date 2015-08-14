<?php
namespace Config;

/**
 * 全局配置文件
 */
class Config
{
    /**
     * 主机名称规则 key为正则，如果没匹配上，则当前主要名表示其运行环境
     * 如果是多台服务器，服务器名称最好是有规律的，直接用正则可搞定，
     * 如有server-1,server-2,..，则直接配置 '/^server-\d+$/' => 'produce'
     * @var array
     */
    public static $hostnameRule = [
        //生产环境
        '/xxxx/' => 'produce'
    ];

    /**
     * 是否开启原始的post请求，也就是php://input功能
     * 注意，如果启用了，则在http请求头中Content-type不能等于form格式类型：application/x-www-form-urlencoded，否则会出现post值为空的情况
     * @var bool
     */
    public static $isOpenOriginalPostInput = false;
}