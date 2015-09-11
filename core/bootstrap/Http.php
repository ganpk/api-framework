<?php
namespace Core\Bootstrap;

use Respect\Validation\Validator;

/**
 * HTTP封闭实体
 */
class Http
{
    /**
     * 请求对象
     * @var swoole_http_request
     */
    public static $request = null;

    /**
     * 响应对象
     * @var swoole_http_response
     */
    public static $response = null;

    /**
     * 调用者真实ip
     * 调用者要将真实ip放到heder的X-Forwarded-For中
     * @var string
     */
    public static $ip = '0.0.0.0';

    /**
     * 客户端唯一身份识别码，app可以对应它的设备id,web可以生成一个唯一cookie
     * 主要处理一些未登陆时的业务，比如匿名下添加购物车
     * 调用者要高此信息加入到header中
     * @var string
     */
    public static $clientIdCard = '';

    /**
     * 当前请求者的用户id
     * 登陆了就将memberId放到header中
     * @var int
     */
    public static $memberId = 0;

    /**
     * 用户签名
     * 登陆成功后就返回，请求放到header中
     * @var string
     */
    public static $memberSignature = '';

    /**
     * 请求的数据包签名
     * 每个请求都需要对请求数据签名，以防篡改
     * @var string
     */
    public static $dataSignature = '';

    /**
     * 客户端系统
     * 调用者要高此信息加入到header中
     * @var string
     */
    public static $clientSystem = '';

    /**
     * 客户端平台标识
     * 调用者要高此信息加入到header中
     * @var string
     */
    public static $clientPlatform = '';

    /**
     * APP版本号
     * @var string
     */
    public static $clientAppVersion = '';

    /**
     * 系统版本号，如当系统是IOS时,8.4.2就是当前IOS的版本号
     * @var string
     */
    public static $clientSystemVersion = '';

    /**
     * APP设置类型，如4s
     * @var string
     */
    public static $clientDeviceModel = '';

    /**
     * HTTP构造方法
     * 禁止实例化
     */
    private function __construct()
    {}

    /**
     * 刷新当前数据
     * @param object $request
     * @param object $response
     */
    public static function refreshHttpData($request, $response)
    {
        echo date('Y-m-d H:i:s').PHP_EOL;
        //保存数值到http类属性中
        self::$request = $request;
        self::$response = $response;

        if (!isset(self::$request->get)) {
            //get不存在则赋上一个空数组，以防后面使用报错
            self::$request->get = array();
        }
        //处理请求的POST数据
        if (\Config\Config::$isOpenOriginalPostInput) {
            //开启了post原始请求
            $postInput = self::$request->rawContent();
            if (!empty($postInput)) {
                $postArr = json_decode($postInput, true);
                self::$request->post = empty($postArr) ? array() : $postArr;
            } else {
                self::$request->post = array();
            }
        } else {
            //没有开启post原始请求，默认已解析好了
            self::$request->post = empty(self::$request->post) ? array() : self::$request->post;
        }

        //获取header固定项到http属性中
        $header = self::$request->header;

        //IP
        self::$ip = '';
        $realRemoteAddrHeaderKey = \Config\Config::$realRemoteAddrHeaderKey;
        if ($realRemoteAddrHeaderKey != '' && !empty($header[$realRemoteAddrHeaderKey])) {
            self::$ip = $header[$realRemoteAddrHeaderKey];
        }
        if (self::$ip == '' && !empty(self::$request->server['remote_addr'])) {
            self::$ip = self::$request->server['remote_addr'];
        }
        //memberId
        self::$memberId = 0;
        if (!empty($header['member_id']) && Validator::int()->min(0)->validate($header['member_id'])) {
            self::$memberId = intval($header['member_id']);
        }
        //用户签名有效
        self::$memberSignature = '';
        if (!empty($header['member_signature']) && Validator::string()->validate($header['member_signature'])) {
            self::$memberSignature = $header['member_signature'];
        }
        //数据包签名
        self::$dataSignature = '';
        if (!empty($header['data_signature']) && Validator::string()->validate($header['data_signature'])) {
            self::$dataSignature = $header['data_signature'];
        }
        //客户端唯一身份标识
        self::$clientIdCard = '';
        if (!empty($header['client_id_card']) && Validator::string()->validate($header['client_id_card'])) {
            self::$clientIdCard = $header['client_id_card'];
        }
        //客户端系统标识
        self::$clientSystem = '';
        if (!empty($header['client_system']) && Validator::string()->validate($header['client_system'])) {
            self::$clientSystem = $header['client_system'];
        }
        //客户端平台标识
        self::$clientPlatform = '';
        if (!empty($header['client_platform']) && Validator::string()->validate($header['client_platform'])) {
            self::$clientPlatform = $header['client_platform'];
        }
        //客户端APP版本标识
        self::$clientAppVersion = '';
        if (!empty($header['client_app_version']) && Validator::string()->validate($header['client_app_version'])) {
            self::$clientAppVersion = $header['client_app_version'];
        }
        //客户端设备类型标识
        self::$clientDeviceModel = '';
        if (!empty($header['client_device_model']) && Validator::string()->validate($header['client_device_model'])) {
            self::$clientDeviceModel = $header['client_device_model'];
        }
        //客户端系统版本标识
        self::$clientSystemVersion = '';
        if (!empty($header['client_system_version']) && Validator::string()->validate($header['client_system_version'])) {
            self::$clientSystemVersion = $header['client_system_version'];
        }
    }
}