<?php
namespace App\Exceptions;

/**
 * 异常基类
 */
class BaseException extends \Exception
{
    /**
     * 响应码详情
     * @var array
     */
    public $responseCodeInfo = [];
    
    /**
     * 设置响应码详情
     * @param array $responseCodeInfo
     * @return \App\Exceptions\BaseException
     */
    public function setResponseCodeInfo($responseCodeInfo)
    {
        $this->responseCodeInfo = $responseCodeInfo;
        return $this;
    }
    
    /**
     * 获取响应码详情
     * @return array
     */
    public function getResponseCodeInfo()
    {
        return $this->responseCodeInfo;
    }
}