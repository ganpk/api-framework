<?php
namespace Framework\Libs;

/**
 * API 基类
 * Class Api
 * @package Framework\Libs
 */
abstract class Api
{
    /**
     * 请求当前api的参数
     * @var array
     */
    public $params = array();

    /**
     * 构造
     */
    public function __construct()
    {
    }

    /**
     * 克隆
     */
    public function __clone()
    {
    }

    /**
     * 获取某一参数
     * @param $key 参数名
     * @param null $dafult 默认值
     * @return null
     * @throws \Framework\Exceptions\ParamException
     */
    public function getParam($key, $default = null)
    {
        $rules = \App\Config\ParamsRule::getRules();
        if (!isset($rules[$key])) {
            throw new \Framework\Exceptions\ParamException("未定义参数规则：{$key}");
        }
        if (!isset($this->params[$key])) {
            $argsCount = func_num_args();
            if ($argsCount == 1) {
                throw new \Framework\Exceptions\ParamException($rules[$key]['desc'] . " （{$key}）参数缺失");
            } else {
                return $default;
            }
        } else {
            return $this->params[$key];
        }
    }

    /**
     * 统一响应
     * @param array $codeArr code数据
     * @param array $result result数据
     * @param array $result extData数据
     * @return array
     */
    public function output($codeArr, $result = array(), $extData = array())
    {
        $responseData = array('codeArr'=>$codeArr,'result'=>$result,'extData'=>$extData);
        return $responseData;
    }
}