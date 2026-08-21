<?php

namespace app\admin\model\check;

use think\Model;

/**
 * Plan
 */
class Plan extends Model
{
    // 表名
    protected $name = 'check_plan';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 追加属性
    protected $append = [
        'admin',
    ];


    public function getAdminAttr($value, $row): array
    {
        return [
            'nickname' => \app\admin\model\Admin::whereIn('id', $row['admin_id'])->column('nickname'),
        ];
    }

    public function getAdminIdAttr($value): array
    {
        if ($value === '' || $value === null) return [];
        if (!is_array($value)) {
            return explode(',', $value);
        }
        return $value;
    }

    public function setAdminIdAttr($value): string
    {
        return is_array($value) ? implode(',', $value) : $value;
    }

    public function car()
    {
        return $this->belongsTo(\app\admin\model\Car::class, 'car_id', 'id');
    }
}