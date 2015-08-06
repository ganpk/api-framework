<?php
namespace Config;

/**
 * 
 * API统一code定义类，里面的属性相当于常量，不要去修改
 * @static
 *
 */
class Code 
{
    /**
     * 成功
     * @var array
     */
    public static $SUCCESS = ['code' => '0', 'msg' => 'success'];
    
    /**
     * 系统异常
     * @var array
     */
    public static $CATCH_EXCEPTION = ['code' => '-1', 'msg' => 'sorry, system catch exception'];
    
    /**
     * 系统繁忙
     * @var array
     */
    public static $SYSTEM_BUSY = ['code'  => '-2', 'msg' => 'system busy, please try again later'];
    
    /**
     * 未找到数据
     * @var array
     */
    public static $EMPTY_DATA = ['code' => '-5', 'msg' => '没有找到数据'];
    
    /**
     * 无效的json数据
     * @var array
     */
    public static $ILLEGAL_JSON = ['code'=>'-6','msg'=>'无效的json数据'];
}

