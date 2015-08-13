<?php
namespace Config;

/**
 * 全局参数规则
 * 为了统一参数KEY的意义和统一验证
 */
class ParamsRule
{
    public static $rules = array(
        
        'productId' => array('desc' => '商品ID','type' => 'int', 'min'=>1),
        
        'memberId'  => array('desc' => '用户ID','type' => 'int', 'min'=>1),
        
        
    );
}