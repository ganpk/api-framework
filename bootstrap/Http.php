<?php
namespace Bootstrap;

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
    public $clientIdCart = '';
    
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
     * HTTP构造方法
     * @param object $request
     * @param object $response
     */
    public function __construct($request, $response)
    {
        //保存数值到http类属性中
        $this->request  = $request;
        $this->response = $response;
        
        if (!isset($this->request->get)) {
            //get不存在则赋上一个空数组，以防后面使用报错
            $this->request->get = array();
        }

        if (\Config\Config::$isOpenOriginalPostInput) {
            //开启了post原始请求
            $postInput = $this->request->rawContent();
            if (!empty($postInput)) {
                $postArr = json_decode($postInput,true);
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
        if (!empty($header['memberId']) && Validator::int()->min(0)->validate($header['memberId'])) {
            //memberId有效
            $this->memberId = $header['memberId'];
        }
        if (!empty($header['memberSignature']) && Validator::string()->validate($header['memberSignature'])) {
            //用户签名有效
            $this->memberSignature = $header['memberSignature'];
        }
        if (!empty($header['clientIdCart']) && Validator::string()->validate($header['clientIdCart'])) {
           //客户端唯一身份标识有效
           $this->clientIdCart = $header['clientIdCart'];
        }
        if (!empty($header['clientSystem']) && Validator::string()->validate($header['clientSystem'])) {
            //客户端系统标识有效
            $this->clientSystem = $header['clientSystem'];
        }
        if (!empty($header['clientPlatform']) && Validator::string()->validate($header['clientPlatform'])) {
            //客户端平台标识有效
            $this->clientPlatform = $header['clientSystem'];
        }
    }
}