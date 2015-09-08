<?php
namespace Core\Bootstrap;

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
    public function __construct(\Core\Bootstrap\Http $http)
    {
        //保存http实例
        $this->http = $http;
        //检查签名
        if (RUN_MOD != 'produce' && !\Config\Secrety::$isCheckSignatureOnTest) {
            //非线上模式，且配置了非线上模式不检查签名
        } else {
            //检查数据包的签名是否正确
            $signature = empty($this->http->dataSignature) ? '' : $this->http->dataSignature;
            $signature = Validator::string()->length(1)->validate($signature) ? $signature : '';
            if ($signature == '' || !\Core\Bootstrap\Auth::isRightPackDataSignature($this->http->request->server['request_uri'], $this->http->request->post, $signature)) {
                //认证未通过
                $this->output(\Config\Code::$AUTH_PACK_DATA_FAIL);
                return;
            }
            //检查用户签名是否正确
            if (!\Core\Bootstrap\Auth::isRightMemberSignature($this->http->memberId, $this->http->memberSignature)) {
                //认证未通过
                $this->output(\Config\Code::$AUTH_MEMBER_FAIL);
                return;
            }
        }

        //分发任务
        try {
            $this->dispatcher();
        } catch (\PDOException $e) {
            //操作数据库出错
            \Core\Libs\Ioc::make('logs')->addError($e->getMessage());
            $this->output(\Config\Code::$CATCH_EXCEPTION);
        } catch (\Exceptions\ParamException $e) {
            //参数出错
            $codeInfo = \Config\Code::$ELLEGAL_PARAMS;
            $codeInfo['msg'] = sprintf($codeInfo['msg'], $e->getMessage());
            $this->output($codeInfo);
        } catch (\Exception $e) {
            //系统异常
            \Core\Libs\Ioc::make('logs')->addError($e->getMessage());
            $this->output(\Config\Code::$CATCH_EXCEPTION);
        }
    }

    /**
     * 统一对外输出方法
     * @param array $codeData 定义的Code项
     * @param array $result 为了统一结构且方便调用者，result必须是对象，不能直接用数组（包含关系数据），
     * @param array $extData 扩展数据，必须是对象
     */
    public function output($codeData = array(), $result = array(), $extData = array())
    {
        //准备参数
        $codeData = empty($codeData) ? array() : $codeData;
        $result = empty($result) ? array() : $result;
        $extData = empty($extData) ? array() : $extData;

        //检查参数是否合法
        if (!Validator::int()->validate($codeData['code'])) {
            //code参数不合法，写入日志
            $codeData = \Config\Code::$CATCH_EXCEPTION;
        }

        //处理msg
        if (empty($codeData['msg']) || !is_string($codeData['msg'])) {
            //没有传，则自动拿注释
            $codeData['msg'] = \Core\Libs\Utility::getCodeAnnotation($codeData['code']);
        }

        //统一响应的数据结构
        $resTplData = [
            'code' => $codeData['code'],
            'msg' => $codeData['msg'],
            'time' => time(),
            'extData' => $extData,
            'result' => $result
        ];

        //转换成驼峰风格
        \Core\Libs\Utility::converToHump($resTplData, \Config\HumpMap::$map);

        //转换数组为对象，主要是统一result和extData下面不直接使用数据
        if (is_array($resTplData['result'])) {
            $resTplData['result'] = json_decode(json_encode($resTplData['result']));
        }
        if (is_array($resTplData['extData'])) {
            $resTplData['extData'] = json_decode(json_encode($resTplData['extData']));
        }

        //响应给客户端
        $this->response(json_encode($resTplData));
    }

    /**
     * 响应
     * @param string $content 响应内容
     * @param int $statusCode 响应状态码
     */
    private function response($content = '', $statusCode = 200)
    {
        //统一将json数据过滤为驼峰风格
        $this->http->response->status = $statusCode;
        $this->http->response->end($content);
    }

    /**
     * 分发任务
     */
    private function dispatcher()
    {
        //uri格式为/v2/products/test

        //安全过滤uri参数
        $this->secretyFilterUri();

        //解析uri
        $uri = strtolower($this->http->request->server['request_uri']);
        $uriParse = explode('/', trim($uri, '/'));
        if (count($uriParse) != 3) {
            //uri不符合格式
            $this->output(\Config\Code::$ELLEGAL_API_URL);
            return;
        }

        //分发给相应处理者的相关参数
        $versionName = $uriParse[0];
        $className = ucfirst($uriParse[1]);
        $methodName = $uriParse[2];

        //检查并过滤参数
        $errorInfo = $this->getCheckParamsErrorInfo();
        if (!empty($errorInfo)) {
            $this->output($errorInfo);
            return;
        }

        //调用相应api，响应数据
        $api = \Core\Libs\AppFactory::api($className, $versionName);
        $api->params = $this->http->request->post;
        $result = $api->{$methodName}();
        //响应数据
        if (empty($result['codeData'])) {
            throw new \Exception('响应数据时没有codeData键');
        }
        $result['result'] = empty($result['result']) ? array() : $result['result'];
        $result['extData'] = empty($result['extData']) ? array() : $result['extData'];
        $this->output($result['codeData'], $result['result'], $result['extData']);
    }

    /**
     * 安全过滤uri参数
     */
    private function secretyFilterUri()
    {
        $uri = $this->http->request->server['request_uri'];
        if (empty($uri)) {
            return;
        }
        //禁止出现.号，以防跨文件执行的安全问题
        $uri = str_replace('.', '', $uri);
        $this->http->request->server['request_uri'] = $uri;
    }

    /**
     * 获取检查参数的错误信息
     * @return array 返回错误信息code数组，如果为空表示参数正确
     */
    private function getCheckParamsErrorInfo()
    {
        $params = $this->http->request->post;
        if (count($params) > 0) {
            foreach ($params as $k => $v) {
                if (empty(\Config\ParamsRule::$rules[$k])) {
                    //规则中没有定义，则踢出参数中
                    unset($this->http->request->post[$k]);
                    continue;
                } else {
                    //在规则中，则验证是否符合规则
                    if (!$this->validatorParam($v, \Config\ParamsRule::$rules[$k])) {
                        $codeArr = \Config\Code::$ELLEGAL_PARAMS;
                        $codeArr['msg'] = \Core\Libs\Utility::getCodeAnnotation(\Config\Code::$ELLEGAL_PARAMS['code']);
                        $codeArr['msg'] = sprintf($codeArr['msg'], \Config\ParamsRule::$rules[$k]['desc'] . '错误');
                        return $codeArr;
                    }
                }
            }
        }
        return array();
    }

    /**
     * 根据规则验证参数是否符合规则
     * @param mixed $value 验证参数值
     * @param array $rule 规则
     * @return boolean
     * TODO:此方法有点low,后面再优化吧
     */
    private function validatorParam($value, $rule)
    {
        foreach ($rule as $k => $v) {
            switch ($k) {
                case 'type':
                    if (!Validator::$v()->validate($value)) {
                        return false;
                    }
                    break;
                case 'min':
                    if ($rule['type'] == 'int') {
                        if ($value < $v) {
                            return false;
                        }
                    } elseif ($rule['type'] == 'string') {
                        if (strlen($value) < $v) {
                            return false;
                        }
                    }
                    break;
                case 'max':
                    if ($rule['type'] == 'int') {
                        if ($value > $v) {
                            return false;
                        }
                    } elseif ($rule['type'] == 'string') {
                        if (strlen($value) > $v) {
                            return false;
                        }
                    }
                    break;
            }
        }
        return true;
    }

}