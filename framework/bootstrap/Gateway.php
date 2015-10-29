<?php
namespace Framework\Bootstrap;

use Framework\Libs\Http;
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
     * 构造方法
     * 静态类禁止实例化
     */
    private function __construct()
    {
    }

    /**
     * 处理请求
     * @param Http $http
     */
    public static function handler()
    {
        //检查签名
        if (!self::checkSignature()) {
            return;
        }

        try {
            //分发任务
            self::dispatcher();
        } catch (\PDOException $e) {
            //操作数据库出错
            \Framework\Libs\Ioc::make('logs')->addError($e->getMessage());
            self::output(\App\Config\Code::$CATCH_EXCEPTION);
        } catch(\Framework\Exceptions\NotLoginException $e) {
            //没有登陆
            self::output(\App\Config\Code::$NOT_LOGIN);
            return;
        } catch (\Framework\Exceptions\ParamException $e) {
            //参数出错
            $codeInfo = \App\Config\Code::$ELLEGAL_PARAMS;
            $codeInfo['msg'] = sprintf($codeInfo['msg'], $e->getMessage());
            self::output($codeInfo);
        } catch (\Exception $e) {
            //系统异常
            \Framework\Libs\Ioc::make('logs')->addError($e->getMessage());
            self::output(\App\Config\Code::$CATCH_EXCEPTION);
        }
    }

    /**
     * 检查签名
     * @return bool
     */
    private static function checkSignature()
    {
        //检查签名
        if (RUN_MOD != 'produce' && !\App\Config\Secrety::instance()->isCheckSignature) {
            //非线上模式，且配置了非线上模式不检查签名
        } else {
            //检查数据包的签名是否正确
            $signature = empty(Http::$dataSignature) ? '' : Http::$dataSignature;
            $signature = Validator::string()->length(1)->validate($signature) ? $signature : '';
            if ($signature == '' || !\Framework\Bootstrap\Auth::isRightPackDataSignature(Http::$request->server['request_uri'], Http::$request->post, $signature)) {
                //认证未通过
                self::output(\App\Config\Code::$AUTH_PACK_DATA_FAIL);
                return false;
            }
            //检查用户签名是否正确
            if (!\Framework\Bootstrap\Auth::isRightMemberSignature(Http::getMemberId(false), Http::$memberSignature)) {
                //认证未通过
                self::output(\App\Config\Code::$AUTH_MEMBER_FAIL);
                return false;
            }
        }
        return true;
    }

    /**
     * 统一对外输出方法
     * @param array $codeData 定义的Code项
     * @param array $result 为了统一结构且方便调用者，result必须是对象，不能直接用数组（包含关系数据），
     * @param array $extData 扩展数据，必须是对象
     */
    private static function output($codeData = array(), $result = array(), $extData = array())
    {
        //获取统一响应数据
        $resData = \Framework\Libs\Utility::getOutputData($codeData, $result, $extData);

        //响应给客户端
        self::response($resData);
    }

    /**
     * 响应
     * @param string $content 响应内容
     * @param int $statusCode 响应状态码
     */
    private static function response($content = '', $statusCode = 200)
    {
        Http::$response->status = $statusCode;
        Http::$response->end($content);
    }

    /**
     * 分发任务
     */
    private static function dispatcher()
    {
        if (Http::$version == '' || Http::$className == '' || Http::$methodName == '') {
            //uri不符合格式
            self::output(\App\Config\Code::$ELLEGAL_API_URL);
            return;
        }

        //检查并过滤参数
        $errorInfo = self::getCheckParamsErrorInfo();
        if (!empty($errorInfo)) {
            self::output($errorInfo);
            return;
        }

        //检查接口是否存在
        $class = '\\App\\Apis\\' . Http::$className;
        if (!self::isExistsApi($class, Http::$methodName)) {
            //请求API不存在
            self::output(\App\Config\Code::$ELLEGAL_API_URL);
            return;
        }
        
        //调用api
        $api = $class::instance();
        $api->params = Http::$post;
        $result = $api->{Http::$methodName}();

        //响应数据
        self::response($result);
    }
    
    /**
     * 是否存在请求api
     * @param unknown $class
     * @param unknown $methodName
     */
    private static function isExistsApi($class,$methodName)
    {
        if (!class_exists($class)) {
            return false;
        }
        if (!method_exists($class, $methodName)) {
            return false;
        }
        return true;
    }

    /**
     * 获取检查参数的错误信息
     * @return array 返回错误信息code数组，如果为空表示参数正确
     */
    private static function getCheckParamsErrorInfo()
    {
        //获取获取外部参数规则
        $rules = \App\Config\ParamsRule::$rules;
        $params = Http::$post;
        if (count($params) > 0) {
            foreach ($params as $k => $v) {
                if (empty($rules[$k])) {
                    //规则中没有定义，则踢出参数中
                    unset(Http::$request->post[$k]);
                    continue;
                } else {
                    //在规则中，则验证是否符合规则
                    if (!self::validatorParam($v, $rules[$k])) {
                        $codeArr = \App\Config\Code::$ELLEGAL_PARAMS;
                        $codeArr['msg'] = sprintf($codeArr['msg'], $rules[$k]['desc'] . '错误');
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
    private static function validatorParam($value, $rule)
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