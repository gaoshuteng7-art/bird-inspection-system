<?php

namespace app\admin\model;

use think\Model;

/**
 * Car
 */
class Car extends Model
{
    // 表名
    protected $name = 'car';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

}