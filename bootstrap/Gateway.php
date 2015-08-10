<?php
namespace Bootstrap;

use Respect\Validation\Validator;

/**
 * 应用网关层
 * - 负责检查认证信息
 * - 分发任务
 * - 响应
 */
class Gateway
{
    /**
     * Http实例 
     * @var Http
     */
    private $http = null;
    
    /**
     * 构造方法
     * @param Http $http
     */
    public function __construct(\Bootstrap\Http $http)
    {
        //保存http实例
        $this->http = $http;
        
        //检查签名
        if (RUN_MOD !='produce' && !\Config\Secrety::$isCheckSignatureOnTest) {
            //非线上模式，且配置了非线上模式不检查签名
        } else {
            //检查数据包的签名是否正确
            $signature = empty($this->http->request->post['signature']) ? '' : $this->http->request->post['signature'];
            $signature = Validator::string()->min(0)->validate($signature) ? $signature : '';
            if ($signature =='' || !\Bootstrap\Auth::isRightPackDataSignature($this->http->request->get, $this->http->request->post, $signature)) {
                //认证未通过
                $this->output(\Config\Code::$AUTH_PACK_DATA_FAIL);
                return;
            }
            //检查用户签名是否正确
            if (!$this->checkMemberSignature()) {
                //认证未通过
                $this->output(\Config\Code::$AUTH_MEMBER_FAIL);
                return;
            }  
        }
        
        //分发任务
        $this->dispatcher();
    }
    
    /**
     * 统一对外输出方法
     * @param array  $codeData 定义的Code项
     * @param object $result 为了统一结构且方便调用者，result必须是对象，不能直接用数组（包含关系数据），
     * @param object $extData  扩展数据，必须是对象
     */
    public function output($codeData = array(), $result = array(), $extData = array())
    {
        //准备参数
        $codeData = empty($codeData) ? array() : $codeData;
        $result   = empty($result)   ? new \stdClass() : $result;
        $extData  = empty($extData)  ? new \stdClass() : $extData;
        
        //检查参数是否合法
        if (!Validator::int()->validate($codeData['code'])) {
            //code参数不合法，写入日志
            $codeData = \Config\Code::$CATCH_EXCEPTION;
        }
        $codeData['msg'] = (empty($codeData['msg']) || !is_string($codeData['msg'])) ? '' : $codeData['msg'];

        //转换数组为对象
        if (is_array($result)) {
            $result = json_decode(json_encode($result));
        }
        if (!is_array($extData)) {
            $extData = json_decode(json_encode($extData));
        }
        
        //统一响应的数据结构
        $resTplData = [
            'code' => $codeData['code'],
            'msg'  => $codeData['msg'],
            'time' => time(),
            'extData' => $extData,
            'result'  => $result
        ];
        
        $this->response(json_encode($resTplData));
    }
    
    /**
     * 响应
     * @param string $content 响应内容
     * @param number $statusCode 响应状态码
     */
    private function response($content = '', $statusCode = 200)
    {
        $this->http->response->status = $statusCode;
        $this->http->response->end($content);
    }
    
    /**
     * 分发任务
     */
    private function dispatcher()
    {
        //uri格式为/v2/item/products/test
        //安全过滤uri参数
        $this->secretyFilterUri();
        //解析uri
        $uri = strtolower($this->http->request->server['request_uri']);
        $uriParse = explode('/', trim($uri,'/'));
        if (count($uriParse) != 3) {
            //uri不符合格式
            return;
        }
        //分发给相应处理者进行处理
        $versionName = $uriParse[0];
        $className   = ucfirst($uriParse[1]);
        $methodName  = $uriParse[2];
        //请求的post参数
        $params = $this->http->request->post;
        //TODO:没有在规则里面的参数全部踢出去，非正式环境开启即可，主要为了规避没按难规则来，私自接外部参数
        //调用相应api，响应数据
        $result = \Lib\AppFactory::api($className, $versionName)->{$methodName}($params);
        $this->output(\Config\Code::$SUCCESS ,$result);
    }
    
    /**
     * 安全过滤uri参数
     */
    private function secretyFilterUri() {
        $uri = $this->http->request->server['request_uri'];
        if (empty($uri)) {
            return;
        }
        //禁止出现.号，以便带来跨文件乱来漏洞
        $uri = str_replace('.', '', $uri);
        $this->http->request->server['request_uri'] = $uri;
    }
}