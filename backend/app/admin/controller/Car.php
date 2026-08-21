<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 车辆管理
 *
 */
class Car extends Backend
{
    /**
     * Car模型对象
     * @var \app\admin\model\Car
     */
    protected $model = null;
    
    protected $preExcludeFields = ['id'];

    protected $quickSearchField = ['id'];

    public function initialize()
    {
        parent::initialize();
        $this->model = new \app\admin\model\Car;
    }


    /**
     * 若需重写查看、编辑、删除等方法，请复制 @see \app\admin\library\traits\Backend 中对应的方法至此进行重写
     */
}