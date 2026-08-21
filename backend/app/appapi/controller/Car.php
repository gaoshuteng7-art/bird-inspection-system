<?php

namespace app\appapi\controller;

use app\common\controller\App;
use think\facade\Db;

class Car extends App{
    public function index(){
        $data = Db::name('car')->select()->toArray();
        $this->success('',$data);
    }
}