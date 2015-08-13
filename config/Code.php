<?php
namespace Config;

/**
 * 
 * API统一code定义类，里面的属性相当于常量，不要去修改
 * @static
 *
 */
class Code 
{
    /**
     * 成功
     * @var array
     */
    public static $SUCCESS = ['code' => '0', 'msg' => 'success'];
    
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
    public static $AUTH_MEMBER_FAIL = ['code'  => '-3', 'msg' => 'authorise the member fail'];
    
    /**
     * 认失数据包失败
     * @var array
     */
    public static $AUTH_PACK_DATA_FAIL = ['code'  => '-4', 'msg' => 'authorise the pack data fail'];

    /**
     * 参数不正确
     * @var array
     */
    public static $ELLEGAL_PARAMS = ['code'  => '-5', 'msg' => '参数异常：%s'];

    /**
     * 请求API不存在
     * @var array
     */
    public static $ELLEGAL_API_URL = ['code' => '-6', 'msg' => '请求API不存在'];
    
    /**
     * 未找到数据
     * @var array
     */
    public static $EMPTY_DATA = ['code'  => '1000','msg' => '没有找到数据'];
    
    /**
     * 无效的json数据
     * @var array
     */
    public static $ILLEGAL_JSON = ['code'  => '1001','msg'=>'无效的json数据'];
}

