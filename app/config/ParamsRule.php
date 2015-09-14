<?php
/**
 * 定义获取外界参数规则文件
 */
$rules = [
    'productId' => array('desc' => '商品ID', 'type' => 'int', 'min' => 1),

    'memberId' => array('desc' => '用户ID', 'type' => 'int', 'min' => 1),
];

return $rules;