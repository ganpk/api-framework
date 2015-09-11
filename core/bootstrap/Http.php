<?php
namespace Core\Bootstrap;

use Respect\Validation\Validator;

/**
 * HTT封装类
 */
class Http
{
    /**
     * 请求对象
     * @var swoole_http_request
     */
    public $request = null;

    /**
     * 响应对象
     * @var swoole_http_response
     */
    public $response = null;

    /**
     * 调用者真实ip
     * 调用者要将真实ip放到heder的X-Forwarded-For中
     * @var string
     */
    public $ip = '0.0.0.0';

    /**
     * 客户端唯一身份识别码，app可以对应它的设备id,web可以生成一个唯一cookie
     * 主要处理一些未登陆时的业务，比如匿名下添加购物车
     * 调用者要高此信息加入到header中
     * @var string
     */
    public $clientIdCard = '';

    /**
     * 当前请求者的用户id
     * 登陆了就将memberId放到header中
     * @var int
     */
    public $memberId = 0;

    /**
     * 用户签名
     * 登陆成功后就返回，请求放到header中
     * @var string
     */
    public $memberSignature = '';

    /**
     * 请求的数据包签名
     * 每个请求都需要对请求数据签名，以防篡改
     * @var string
     */
    public $dataSignature = '';

    /**
     * 客户端系统
     * 调用者要高此信息加入到header中
     * @var string
     */
    public $clientSystem = '';

    /**
     * 客户端平台标识
     * 调用者要高此信息加入到header中
     * @var string
     */
    public $clientPlatform = '';

    /**
     * APP版本号
     * @var string
     */
    public $clientAppVersion = '';

    /**
     * 系统版本号，如当系统是IOS时,8.4.2就是当前IOS的版本号
     * @var string
     */
    public $clientSystemVersion = '';

    /**
     * APP设置类型，如4s
     * @var string
     */
    public $clientDeviceModel = '';

    /**
     * HTTP构造方法
     * @param object $request
     * @param object $response
     */
    public function __construct($request, $response)
    {
        //保存数值到http类属性中
        $this->request = $request;
        $this->response = $response;

        if (!isset($this->request->get)) {
            //get不存在则赋上一个空数组，以防后面使用报错
            $this->request->get = array();
        }

        if (\Config\Config::$isOpenOriginalPostInput) {
            //开启了post原始请求
            $postInput = $this->request->rawContent();
            if (!empty($postInput)) {
                $postArr = json_decode($postInput, true);
                $this->request->post = empty($postArr) ? array() : $postArr;
            } else {
                $this->request->post = array();
            }
        } else {
            //没有开启post原始请求，默认已解析好了
            $this->request->post = empty($this->request->post) ? array() : $this->request->post;
        }

        //获取header固定项到http属性中
        $header = $this->request->header;
        if (!empty($header['member_id']) && Validator::int()->min(0)->validate($header['member_id'])) {
            //memberId有效
            $this->memberId = intval($header['member_id']);
        }
        if (!empty($header['member_signature']) && Validator::string()->validate($header['member_signature'])) {
            //用户签名有效
            $this->memberSignature = $header['member_signature'];
        }
        if (!empty($header['data_signature']) && Validator::string()->validate($header['data_signature'])) {
            //数据包签名有效
            $this->dataSignature = $header['data_signature'];
        }
        if (!empty($header['client_id_card']) && Validator::string()->validate($header['client_id_card'])) {
            //客户端唯一身份标识有效
            $this->clientIdCard = $header['client_id_card'];
        }
        if (!empty($header['client_system']) && Validator::string()->validate($header['client_system'])) {
            //客户端系统标识有效
            $this->clientSystem = $header['client_system'];
        }
        if (!empty($header['client_platform']) && Validator::string()->validate($header['client_platform'])) {
            //客户端平台标识有效
            $this->clientPlatform = $header['client_platform'];
        }
        if (!empty($header['client_platform']) && Validator::string()->validate($header['client_platform'])) {
            //客户端平台标识有效
            $this->clientPlatform = $header['client_platform'];
        }
        if (!empty($header['client_app_version']) && Validator::string()->validate($header['client_app_version'])) {
            //客户端APP版本标识
            $this->clientAppVersion = $header['client_app_version'];
        }
        if (!empty($header['client_device_model']) && Validator::string()->validate($header['client_device_model'])) {
            //客户端设备类型标识
            $this->clientDeviceModel = $header['client_device_model'];
        }
        if (!empty($header['client_system_version']) && Validator::string()->validate($header['client_system_version'])) {
            //客户端系统版本标识
            $this->clientSystemVersion = $header['client_system_version'];
        }
    }
}