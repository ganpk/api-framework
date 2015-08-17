<?php
namespace Core\Libs;

class Utility
{
    /**
     * 将数组转换为驼峰风格
     * 它会自动将key为_字符去掉并将之后的字符大写，如果传了map数据，则会在转换后将相应key做映射，如userid=>userId，主要是为了解决有的key不是按_分割的
     * @param array $arr
     * @param array $map
     * @return array
     */
    public static function converToHump(array &$arr, array &$map = array())
    {
        if (empty($arr)) {
            return array();
        }
        //循环处理key
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

    /**
     * 获取某code的注释
     * @param int $code
     * @return string
     */
    public static function getCodeAnnotation($code = -1)
    {
        //获取配置code项，一次性提取到内存中，虽然占内存，但是提升了效率，第一次会慢一些
        static $codes = array();
        if (empty($codes)) {
            $reflect = new \ReflectionClass('\Config\Code');
            $codesArr = $reflect->getStaticProperties();
            foreach ($codesArr as $k => $v) {
                //获取此code注释
                $codeInfo = $v;
                $proComment = $reflect->getProperty($k)->getDocComment();
                $proComment = preg_replace('/\s/','',$proComment);
                $proComment = substr($proComment,4,strpos($proComment,'*@var')-4);
                $codeInfo['comment'] = $proComment;
                $codes[$v['code']] = $codeInfo;
            }
        }
        //获取此code注释key名称
        if (empty($codes[$code])) {
            throw new \Exception("code:{$code},未找到");
        }
        $comment = $codes[$code]['comment'];
        return $comment;
    }
}