<?php
namespace Core\Libs;

/**
 * JSON 相关工具
 * Class Json
 * @package Core\Libs
 */
class Json
{
    /**
     * 将数组转换为驼峰风格
     * 它会自动将key为_字符去掉并将之后的字符大写，如果传了map数据，则会在转换后将相应key做映射，如userid=>userId，主要是为了解决有的key不是按_分割的
     * @param array/string $json
     * @param array $map 映射项
     */
    public static function converToHump(array &$arr, array &$map = array())
    {
        print_r($arr);
        if (empty($arr)) {
            return array();
        }
        $newArr = array();
        foreach ($arr as $k => $v) {
            $splitArr = explode('_', $k);
            $newKey = $k;
            if (count($splitArr) > 1) {
                //存在下划线
                foreach ($splitArr as $kk => $vv) {
                    //将除了第一个元素外的所有元素首字母大写
                    if ($kk == 0) {
                        continue;
                    }
                    $splitArr[$kk] = ucfirst($vv);
                }
                $newKey = implode('', $splitArr);
            }
            if (isset($map[$newKey])) {
                //存在映射
                $newKey = $map[$newKey];
            }
            $newArr[$newKey] = $v;
            if (is_array($v) && !empty($v)) {
                self::converToHump($newArr[$newKey], $map);
            }
        }
        $arr = $newArr;
    }
}