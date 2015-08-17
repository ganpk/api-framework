<?php
namespace Config;

/**
 * API统一code定义类，里面的属性相当于常量，不要在程序中去修改
 * 注意：里面的注释格式必须一致，因为注释会作为响应的msg值,如果不想用注释，则在数组里面添加msg元素
 * @static
 */
class Code 
{
    /**
     * success
     * @var array
     */
    public static $SUCCESS = ['code' => '0'];
    
    /**
     * 系统异常
     * @var array
     */
    public static $CATCH_EXCEPTION = ['code' => '-1', 'msg' => 'sorry, system catch exception'];
    
    /**
     * 系统繁忙
     * @var array
     */
    public static $SYSTEM_BUSY = ['code'  => '-2', 'msg' => 'system busy, please try again later'];
    
    /**
     * 认证用户失败
     * @var array
     */
    public static $AUTH_MEMBER_FAIL = ['code'  => '-3'];
    
    /**
     * 认失数据包失败
     * @var array
     */
    public static $AUTH_PACK_DATA_FAIL = ['code'  => '-4'];

    /**
     * 参数异常：%s
     * @var array
     */
    public static $ELLEGAL_PARAMS = ['code'  => '-5'];

    /**
     * 请求API不存在
     * @var array
     */
    public static $ELLEGAL_API_URL = ['code' => '-6'];
    
    /**
     * 未找到数据
     * @var array
     */
    public static $EMPTY_DATA = ['code'  => '1000'];
    
    /**
     * 无效的json数据
     * @var array
     */
    public static $ILLEGAL_JSON = ['code'  => '1001'];
}

