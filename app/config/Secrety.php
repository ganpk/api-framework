<?php
namespace App\Config;

/**
 * 安全相关配置类
 */
class Secrety extends \Framework\Libs\Config
{
    /**
     * 是否开启检查签名
     * 生产环境一定要开启
     * @var boolean true表示检查，反之亦然
     */
    public $isCheckSignature = false;

    /**
     * 签名数据包的token
     * @var string
     */
    public $signaturePackToken = '123456';

    /**
     * 签名用户的token
     * @var string
     */
    public $signatureMemberToken = '123456';
}
