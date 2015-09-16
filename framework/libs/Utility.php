<?php
namespace Framework\Libs;

class Utility
{
    /**
     * 将数组转换为驼峰风格
     * 它会自动将key为_字符去掉并将之后的字符大写，如果传了map数据，则会在转换后将相应key做映射，如userid=>userId，主要是为了解决有的key不是按_分割的
     * @param array $arr
     * @return array
     */
    public static function converToHump(array $arr)
    {
        static $map = array();
        if (empty($map)) {
            $map = require APP_PATH . '/config/HumpMap.php';
        }

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
                $newArr[$newKey] = self::converToHump($newArr[$newKey]);
            }
        }
        return $newArr;
    }

    /**
     * 获取某code的注释
     * @param int $code
     * @return mixed
     * @throws \Exception
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
                $proComment = preg_replace('/\s/', '', $proComment);
                $proComment = substr($proComment, 4, strpos($proComment, '*@var') - 4);
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

    /**
     * 将code的注释刷到code的msg中
     */
    public static function refreshCodeAnnotation()
    {
        $codeClass = '\App\Config\Code';
        $reflect = new \ReflectionClass($codeClass);
        $codesArr = $reflect->getStaticProperties();
        foreach ($codesArr as $k => $v) {
            if (!empty($v['msg'])) {
                continue;
            }
            //获取此code注释
            $proComment = $reflect->getProperty($k)->getDocComment();
            $proComment = preg_replace('/\s/', '', $proComment);
            $proComment = substr($proComment, 4, strpos($proComment, '*@var') - 4);
            $v['msg'] = $proComment;
            $codeClass::$$k = $v;
        }
    }

    /**
     * 统一对外输出数据方法
     * @param array $codeData 定义的Code项
     * @param array $result 为了统一结构且方便调用者，result必须是对象，不能直接用数组（包含关系数据），
     * @param array $extData 扩展数据，必须是对象
     * @return array
     */
    public static function getOutputData($codeData = array(), $result = array(), $extData = array())
    {
        //准备参数
        $codeData = empty($codeData) ? array() : $codeData;
        $result = empty($result) ? array() : $result;
        $extData = empty($extData) ? array() : $extData;

        //检查参数是否合法
        if (!\Respect\Validation\Validator::int()->validate($codeData['code'])) {
            $codeData = \App\Config\Code::$CATCH_EXCEPTION;
        }

        //统一响应的数据结构
        $resTplData = [
            'code' => $codeData['code'],
            'msg' => $codeData['msg'],
            'time' => time(),
            'extData' => $extData,
            'result' => $result
        ];

        //转换成驼峰风格
        $resTplData = \Framework\Libs\Utility::converToHump($resTplData);

        //转换数组为对象，主要是统一result和extData下面不直接使用数据
        if (is_array($resTplData['result'])) {
            $resTplData['result'] = json_decode(json_encode($resTplData['result']));
        }
        if (is_array($resTplData['extData'])) {
            $resTplData['extData'] = json_decode(json_encode($resTplData['extData']));
        }
        $responseBody = json_encode($resTplData);
        return $responseBody;
    }
}