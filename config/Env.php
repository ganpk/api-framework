<?php
/**
 * 环境配置文件
 * 主机名称规则 key为正则，如果没匹配上，则当前主机名表示其运行环境
 * 如果是多台服务器，服务器名称最好是有规律的，直接用正则可搞定
 * 如有server-1,server-2,..，台服务是正式的，则直接配置 '/^server-\d+$/' => 'produce'
 */
$envConfig = [
    //生产环境
    '/xxxx/' => 'produce'
];

return $envConfig;