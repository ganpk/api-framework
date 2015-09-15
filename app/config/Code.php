<?php
namespace App\Config;

/**
 * API统一code定义类
 * 注意1：里面的注释格式必须一致，因为注释会作为响应的msg值,如果不想用注释，则在数组里面添加msg元素
 * 注意2：约定规则，code小于0表示系统错误，-100 < code < 0 不需要展示给app观看的错误，-200 < code < -100 表示需要和app交互的错误，如-201表示未登陆，此时app就要需要调用登陆
 *        大于0的错误为应用程序定制的错误
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
    public static $CATCH_EXCEPTION = ['code' => '-1', 'msg' => 'sorry, system catch exception'];

    /**
     * 系统繁忙
     * @var array
     */
    public static $SYSTEM_BUSY = ['code' => '-2', 'msg' => 'system busy, please try again later'];

    /**
     * 认证用户失败
     * @var array
     */
    public static $AUTH_MEMBER_FAIL = ['code' => '-3'];

    /**
     * 认失数据包失败
     * @var array
     */
    public static $AUTH_PACK_DATA_FAIL = ['code' => '-4'];

    /**
     * 请求API不存在
     * @var array
     */
    public static $ELLEGAL_API_URL = ['code' => '-5'];

    /**
     * 用户没有登陆
     * @var array
     */
    public static $NOT_LOGIN = ['code' => '-201'];

    /**
     * 参数异常：%s
     * @var array
     */
    public static $ELLEGAL_PARAMS = ['code' => '-202'];

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

