<?php
declare (strict_types = 1);

namespace app\appapi\controller;

use think\captcha\facade\Captcha;
use think\facade\Cache;
use app\BaseController;
use app\common\controller\Api;
use think\facade\Db;
use ba\Random;

class Index extends Api
{
    public function captcha(){
        $captcha = Captcha::create();
        $key = 'captcha_' . uniqid();
        Cache::set($key, strtolower($captcha['code']), 300);    //5分钟有效
        $this->success('',['img'=>$captcha['img'],'token'=>$key]);
    }
    public function login(){
        $params = $this->request->param();
        $v = Cache::get($params['virifyToken']);
        if($params['verify'] != $v){
            $this->error('验证码错误');
        }
        //根据用户名查询是否有用户
        $admin = Db::name('admin')->where('username',$params['username'])->find();
        if(!$admin){
            $this->error('用户不存在');
        }
        if($admin['password'] != encrypt_password($params['password'],$admin['salt'])){
            $this->error('密码错误');
        }
        // 随机生成token
        $token = Random::uuid();
        // token和用户进行绑定
        $expire_time = 3600;
        Cache::set($token,$admin['id'], $expire_time);
        // token下发给客户端
        $this->success('登陆成功',[
            'name'  => $admin['nickname'],
            'avatar' => $admin['avatar'],
            'token' => $token,
            'expire_time' => time() + $expire_time
        ]);
    }
    public function logout(){
        $token = $this->request->header('user-token');
        Cache::delete($token);
        $this->success('退出成功');
    }
    public function index()
    {
        
        return '您好！这是一个[appapi]示例应用';
    }
    public function protocel(){
        $data = Db::name('protocel')->select()->toArray();
        // $this->error('请求失败');
        foreach ($data as $key => &$value) {
            $value['content'] = html_entity_decode($value['content']);
        }
        $this->success('',$data);
    }
}
