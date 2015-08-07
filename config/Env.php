<?php
namespace Config;

/**
 * 运行环境配置文件
 */
class Env
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
}