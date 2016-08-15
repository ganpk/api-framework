<?php
namespace Framework\Libs;

/**
 * 自定义参数过滤类
 * @author 志杨
 *
 */
class Validator {
    
    /**
     * 长整型
     * @param unknown $input
     */
    public static function isLongInt($input)
    {
        if (is_numeric($input) && $input > 0 && !strpos((string)$input,'.')) {
            return true;
        }
        return false;
    }
    
    /**
     * 身份证
     * @param unknown $vStr
     * @return boolean
     */
    public static function isIdCard($vStr)
    {
        if(empty($vStr)){
            // 可以为空
            return true;
        }
        $vCity = array(
            '11','12','13','14','15','21','22',
            '23','31','32','33','34','35','36',
            '37','41','42','43','44','45','46',
            '50','51','52','53','54','61','62',
            '63','64','65','71','81','82','91'
        );
        if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;
        if (!in_array(substr($vStr, 0, 2), $vCity)) return false;
        $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
        $vLength = strlen($vStr);
        if ($vLength == 18)
        {
            $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
        } else {
            $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
        }
        if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
        if ($vLength == 18)
        {
            $vSum = 0;
            for ($i = 17 ; $i >= 0 ; $i--)
            {
                $vSubStr = substr($vStr, 17 - $i, 1);
                $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
            }
            if($vSum % 11 != 1) return false;
        }
        return true;
    }
    
    /**
     * 手机号
     * @param unknown $mobile
     * @return boolean
     */
    public static function isMobile($mobile)
    {
        return  ctype_digit($mobile) && preg_match('/^1[3|4|5|7|8][0-9]\d{8}$/',$mobile);
    }
    
    /**
     * 正常字符 （字母数字汉字）
     * @param unknown $input
     */
    public static function isNormalCharacter($input)
    {
        if (strlen($input) == 0) {
            return false;
        }
        if (preg_match("/^[A-Za-z0-9\x{4e00}-\x{9fa5}]+$/u", $input)) {
            return true;
        }
        return false;
    }
    
    /**
     * 是否在数组中
     * @param unknown $need
     * @param unknown $arr
     */
    public static function isInArray($need, $arr)
    {
        if (in_array($need, $arr)) {
            return true;
        }
        return false;
    }
}