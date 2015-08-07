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
    
    public function __construct($http)
    {
        //保存http实例
        $this->http = $http;
        
        //认证请求信息
        $this->authoriseRequest();
        
        //分发任务
        
        //响应数据
    }
    
    /**
     * 认证请求信息
     */
    private function authoriseRequest(){
        //检查数据包的签名是否正确
        $this->checkPackSignature();
        
        //检查用户签名是否正确
        $this->checkMemberSignature();
    }
    
    /**
     * 检查数据包签名是否正确，防止被篡改
     */
    private function checkPackSignature()
    {
        $checkRes = true;
        $checkRes = \Bootstrap\Auth::
    }    
    
    /**
     * 检查用户签名是否正确
     */
    private function checkMemberSignature()
    {
        $checkRes = true;
        if (Validator::int()->notEmpty()->min(0)->validate($this->http->request->header['memberId'])) {
            //如果传有uerId，则检查用户签名是否正确
            $memberId = $this->http->request->header['memberId'];
            $signature = $this->http->request->header['memberSignature'];
            $checkRes = \Bootstrap\Auth::isRightMemberSignature($memberId, $signature);
        } else {
            //没有用户，不存在用户也就不存在用户签名的说法
            $checkRes = true;
        }
        if (!$checkRes) {
            //认证未通过
            $this->output(\Config\Code::$AUTH_MEMBER_FAIL);
        }
    }
    
    /**
     * 统一对外输出方法
     * @param \Config\Code  $codeData 定义的Code项
     * @param object $result 为了统一结构且方便调用者，result必须是对象，不能直接用数组（包含关系数据），
     * @param object $extData  扩展数据，必须是对象
     */
    public function output($codeData = array(), $result = array(), $extData = array())
    {
        //准备参数
        $codeData = empty($codeData) ? array() : (object)$codeData;
        $result   = empty($result) ? new stdClass() : $result;
        $extData  = empty($extData) ? new stdClass() : $extData;
        
        //检查参数是否合法
        if (Validator::int()->notEmpty()->validate($codeData['code'])) {
            //code参数不合法，写入日志
            $codeData = \Config\Code::$CATCH_EXCEPTION;
        }
        $codeData['msg'] = (empty($codeData['msg']) || is_string($codeData['msg'])) ? '' : $codeData['msg'];

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
        
        $this->response(json_ecode($resTplData));
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
}