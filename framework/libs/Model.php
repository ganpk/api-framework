<?php
namespace Framework\Libs;

use \Illuminate\Database\Eloquent\Model as EqtModel;

/**
 * model 基类
 * Class baseModel
 * @package Framework\Libs;
 */
abstract class Model extends EqtModel
{
    //自动维护数据库表的 created_at 和 updated_at 字段
    public $timestamps = false;
}