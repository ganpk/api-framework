<?php
namespace App\Models;

/**
 * 用户表
 * Class Member
 * @package Apps\V1\Models
 */
class Member extends \App\Models\BaseModel
{
    //表名
    protected $table = 'member';

    //主键名
    protected $primaryKey = 'userid';
}