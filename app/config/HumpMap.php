<?php
/**
 * 驼峰映射配置
 * 主要解决某些不规则的key，如数据库字段为userid，这种得转换成userId
 */

$map = [
    'userid' => 'userId',
    'cartMonty' => 'cartMoney'
];

return $map;