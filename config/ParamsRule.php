<?php
namespace Config;

/**
 * 全局参数规则
 * 为了统一参数KEY的意义和统一验证
 * 程序会通过反射取它的注释作为返回的的msg值
 * 注意：这里必须严格按示例方式进行注释
 */
class ParamsRule
{
    public static $rules = array(
        
        'productId' => array('desc' => '商品ID','type' => 'int', 'min'=>1),
        
        'memberId'  => array('desc' => '用户ID','type' => 'int', 'min'=>1),
        
        
    );
}