<?php
namespace App\Config;

/**
 * 定义获取外界参数规则文件
 * Class ParamsRule
 *
 * @package App\Config
 */
class ParamsRule
{

    /**
     * 获取参数过滤规则
     */
    public static function getRules()
    {
        $rules = [
            'productId' => [
                'desc' => '商品ID',
                'rule' => [
                    'type' => 'int',
                    'conds' => [
                        'min' => [1],
                    ]
                ]
            ],
            
            'memberId' => [
                'desc' => '用户ID',
                'rule' => [
                    'type' => 'int',
                    'conds' => [
                        'min' => [1],
                    ]
                ]
            ],
            
            'currentPage' => [
                'desc' => '当前页数',
                'rule' => [
                    'type' => 'int',
                    'conds' => [
                        'min' => [1],
                    ]
                ]
            ],
            
            'pageSize' => [
                'desc' => '每页条数',
                'rule' => [
                    'type' => 'int',
                    'conds' => [
                        'min' => [1],
                    ]
                ]
            ],
            
            'activityId' => [
                'desc' => '特卖ID',
                'rule' => [
                    'type' => 'int',
                    'conds' => [
                        'min' => [1],
                    ]
                ]
            ],
            
            'crowdFundStatus' => [
                'desc' => '伙拼状态',
                'func' => function ($value) {
                    $allow = [
                        'funding',
                        'foreshow'
                    ];
                    if (in_array($value, $allow)) {
                        return true;
                    }
                    return false;
                }
            ]
        ];
        return $rules;
    }
}