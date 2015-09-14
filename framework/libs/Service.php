<?php
namespace Framework\Libs;

/**
 * service 基类
 * Class BaseModule
 * @package Framework\Libs;
 */
class Service
{
    /**
     * 获取某一参数
     * @param $key 参数名
     * @param null $dafult 默认值
     * @return string|int
     * @throws \Framework\Exceptions\ParamException
     */
    final protected function getParam($key, $dafult = null)
    {
        if (!isset($this->params[$key])) {
            $argsCount = func_num_args();
            if ($argsCount == 1) {
                throw new \Framework\Exceptions\ParamException(\App\Config\ParamsRule::$rules[$key]['desc'] . "参数{$key}缺失");
            } else {
                return $dafult;
            }
        } else {
            return $this->params[$key];
        }
    }
}