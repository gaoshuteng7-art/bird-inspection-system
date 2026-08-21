<?php

namespace app\appapi\controller;

use app\common\controller\App;
use think\facade\Db;

class Admin extends App{
    public function index(){
        $data = Db::name('admin')->field('id,nickname')->select()->toArray();
        $this->success('',$data);
    }
}