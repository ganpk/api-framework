<?php
namespace Apps\V2\Config;

/**
 * 某版本下的全局配置文件
 */
class Config
{
    /**
     * 当前版本继承到某版本，没有则为空
     * @var string
     */
    public static $extends = 'v1';    
}