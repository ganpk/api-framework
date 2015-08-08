<?php
namespace v1\lib;

/**
 * 参数规则
 * TODO:参数规则参评，再想下怎么实现好
 */
class ParamsRule
{
    /**
     * 全局规则
     */
    public static $rules = [
         /**
          * 商品id
          */
        'productId' => ['type'=>'int','min'=>1],
        
        /**
         * 邮箱
         */
        'email' => ['type'=>'email'],
        
        
    ];
}