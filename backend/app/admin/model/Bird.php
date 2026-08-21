<?php

namespace app\admin\model;

use think\Model;

/**
 * Bird
 */
class Bird extends Model
{
    // 表名
    protected $name = 'bird';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    protected static function onAfterInsert($model)
    {
        if ($model->weigh == 0) {
            $pk = $model->getPk();
            if (strlen($model[$pk]) >= 19) {
                $model->where($pk, $model[$pk])->update(['weigh' => $model->count()]);
            } else {
                $model->where($pk, $model[$pk])->update(['weigh' => $model[$pk]]);
            }
        }
    }
}