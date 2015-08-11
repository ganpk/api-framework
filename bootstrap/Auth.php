<?php
namespace Bootstrap;

use Respect\Validation\Validator;

/**
 * 认证类
 */
class Auth 
{
    /**
     * 获取用户签名
     * @param number $memberId
     * @return string 签名字符串
     */
    public static function getMemberSignature($memberId)
    {
        if (Validator::int()->min(0)) {
            return substr(md5($memberId.\Config\Secrety::$signatureMemberToken),8,16);
        }
        return '';
    }    
    
    /**
     * 用户签名是否正确
     * @param number $memberId
     * @param string $signature
     * @return boolean true表示正确，false反之
     */
    public static function isRightMemberSignature($memberId = 0, $signature = '')
    {
        if (self::getMemberSignature($memberId) == $signature) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 数据包签名是否正确
     * @param string $uri server中的uri参数
     * @param array $post 请求的post参数
     * @param string $signature 签名
     * @return boolean true表示正确，false反之
     */
    public static function isRightPackDataSignature($uri, $post, $signature = '')
    {
        //$post按key的asscii码升序，最后再k1=v1&k2=v2的形式连接起来，放到$grather变量中，最后再md5(token + $uri + 连接后的字符串$grather)，
        //为什么要用asscii码排序呢？是因为有的语言的map会自动按key的asscii码升序
        $gather = '';
        if (!empty($post) && is_array($post)) {
            ksort($post);
            $post = array_reverse($post);
            foreach($post as $k => $v){
                if($gather !=  ""){
                    $gather .= '&';
                }
                $gather .= "{$k}={$v}";
            }
        }
        if ($signature == md5(\Config\Secrety::$signaturePackToken.$uri.$gather)) {
            return true;
        }
        return false;
    }
}
