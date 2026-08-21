<?php
namespace app\common\controller;

use app\common\controller\Api;
use think\facade\Cache;

class App extends Api{
    public function initialize()
    {
        parent::initialize();
        // token校验
        $token = $this->request->header('user-token','');
        $uid = Cache::get($token);
        if(!$uid){
            $this->error('请先登录',[],302);
        }
    }
}