<?php

namespace app\appapi\controller;

use app\common\controller\App;
use think\facade\Db;

class Bird extends App
{
    public function index()
    {
        $this->success('', Db::name('bird')->field('id,name')->order('weigh asc,id asc')->select()->toArray());
    }
}
