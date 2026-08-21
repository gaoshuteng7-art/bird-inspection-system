<?php

namespace app\admin\model\check;

use think\Model;

/**
 * Log
 */
class Log extends Model
{
    // 表名
    protected $name = 'check_log';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;


    public function setTimeAttr($value): string
    {
        return $value ? date('H:i:s', strtotime($value)) : '';
    }

    public function getActiveTypeAttr($value): array
    {
        if ($value === '' || $value === null) return [];
        if (!is_array($value)) {
            return explode(',', $value);
        }
        return $value;
    }

    public function setActiveTypeAttr($value): string
    {
        return is_array($value) ? implode(',', $value) : $value;
    }

    public function getCuoshiAttr($value): array
    {
        if ($value === '' || $value === null) return [];
        if (!is_array($value)) {
            return explode(',', $value);
        }
        return $value;
    }

    public function setCuoshiAttr($value): string
    {
        return is_array($value) ? implode(',', $value) : $value;
    }

    public function checkPlan()
    {
        return $this->belongsTo(\app\admin\model\check\Plan::class, 'check_plan_id', 'id');
    }

    public function bird()
    {
        return $this->belongsTo(\app\admin\model\Bird::class, 'bird_id', 'id');
    }
}