<?php
namespace App\Models;

/**
 * 模型基类
 * Class BaseModel
 * @package Apps\BaseModel
 */
class BaseModel extends \Framework\Libs\Model
{
    /**
     * 将查询结果对象转换为数组
     * (non-PHPdoc)
     * @see \Illuminate\Database\Eloquent\Model::toArray()
     */
    public function toArray() {
        $res = [];
        if (count($this)) {
            $res = parent::toArray();
        }
        return $res;
    }
}
