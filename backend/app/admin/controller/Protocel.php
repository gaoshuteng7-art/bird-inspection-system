<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 协议管理
 *
 */
class Protocel extends Backend
{
    /**
     * Protocel模型对象
     * @var \app\admin\model\Protocel
     */
    protected $model = null;
    
    protected $preExcludeFields = ['id'];

    protected $quickSearchField = ['id'];

    public function initialize()
    {
        parent::initialize();
        $this->model = new \app\admin\model\Protocel;
        $this->request->filter('trim,htmlspecialchars');
    }


    /**
     * 若需重写查看、编辑、删除等方法，请复制 @see \app\admin\library\traits\Backend 中对应的方法至此进行重写
     */
}