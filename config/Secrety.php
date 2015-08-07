<?php
namespace Config;

/**
 * 安全相关配置类
 */
class Secrety
{
    /**
     * 在非生产环境中是否开启检查签名
     * @var boolean true表示检查，反之亦然
     */
    public static $isCheckSignatureOnTest = false;
    
    /**
     * 签名数据包的token
     * @var string
     */
    public static $signaturePackToken = '123456';
    
    /**
     * 签名用户的token
     * @var string
     */
    public static $signatureMemberToken = '123456';
}
