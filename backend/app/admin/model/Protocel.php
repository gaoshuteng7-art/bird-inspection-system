<?php

namespace app\admin\model;

use think\Model;

/**
 * Protocel
 */
class Protocel extends Model
{
    // 表名
    protected $name = 'protocel';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;


    public function getContentAttr($value): string
    {
        return !$value ? '' : htmlspecialchars_decode($value);
    }
}