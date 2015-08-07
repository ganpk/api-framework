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
        if ($this->getMemberSignature($memberId) == $signature) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 数据包签名是否正确
     * @param array $get
     * @param array $post
     * @param string $signature
     * @return boolean true表示正确，false反之
     */
    public static function isRightPackDataSignature($get = array(), $post = array(), $signature = '') 
    {
        //$get按key的asscii码升序，$post也是，最后再k=v的形式连接起来，先连接get再连接post,放到$grather变量中，最后再md5(token+连接后的字符串$grather)，
        //为什么要用asscii码排序呢？是因为有的语言的map会自动按key的asscii码升序
        
        //TODO:如注释
        $gather = '';
        
        if ($signature == md5(\Config\Secrety::$signaturePackToken .$gather)) {
            return true;
        }
        return false;
    }
}
