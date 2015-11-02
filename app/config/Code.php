<?php
namespace App\Config;

/**
 * API统一code定义类
 * 注意1：里面的注释格式必须一致，因为注释会作为响应的msg值,如果不想用注释，则在数组里面添加msg元素
 * 注意2：约定规则，code 0表示成功 10-100 表示系统错误，它可以不需要展示给app观看的错误，100 < code < 200 表示需要和app交互的错误，如101表示未登陆，此时app就要需要调用登陆
 *        大于1000的错误为应用程序定制的错误
 * @static
 */
class Code
{
    /**
     * success
     * @var array
     */
    public static $SUCCESS = ['code' => '0', 'msg' => 'success'];

    /**
     * 系统异常
     * @var array
     */
    public static $CATCH_EXCEPTION = ['code' => '10', 'msg' => 'sorry, system catch exception'];

    /**
     * 系统繁忙
     * @var array
     */
    public static $SYSTEM_BUSY = ['code' => '11', 'msg' => 'system busy, please try again later'];

    /**
     * 认证用户失败
     * @var array
     */
    public static $AUTH_MEMBER_FAIL = ['code' => '12'];

    /**
     * 认失数据包失败
     * @var array
     */
    public static $AUTH_PACK_DATA_FAIL = ['code' => '13'];

    /**
     * 请求API不存在
     * @var array
     */
    public static $ELLEGAL_API_URL = ['code' => '14'];

    /**
     * 响应内容错误，如果响应格式，会将此上报给统计中心
     * @var array
     */
    public static $ELLEGAL_RESPONSE_CONTENT = ['code' => '15','msg'=>'响应内容格式不正确:%s'];
    
    /**
     * 用户没有登陆
     * @var array
     */
    public static $NOT_LOGIN = ['code' => '101'];

    /**
     * 参数异常：%s
     * @var array
     */
    public static $ELLEGAL_PARAMS = ['code' => '102'];

    /**
     * 未找到数据
     * @var array
     */
    public static $EMPTY_DATA = ['code' => '1000'];

    /**
     * 无效的json数据
     * @var array
     */
    public static $ILLEGAL_JSON = ['code' => '1001'];
}

