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
}