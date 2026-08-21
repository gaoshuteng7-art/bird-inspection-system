<?php

namespace app\appapi\controller\check;

use app\common\controller\App;
use think\facade\Db;

class Log extends App
{
    public function add()
    {
        $p = $this->request->post();
        $planId = (int)($p['check_plan_id'] ?? 0);
        $birdId = (int)($p['bird_id'] ?? 0);
        if (!$planId || !$birdId || empty($p['time']) || empty($p['area'])) $this->error('请完整填写巡视记录');
        if (!Db::name('check_plan')->where('id', $planId)->find()) $this->error('巡视计划不存在');
        if (!Db::name('bird')->where('id', $birdId)->find()) $this->error('鸟种不存在');

        $id = Db::name('check_log')->insertGetId([
            'check_plan_id' => $planId,
            'time' => date('H:i:s', strtotime($p['time'])),
            'area' => $p['area'],
            'bird_id' => $birdId,
            'min_num' => (int)($p['min_num'] ?? 0),
            'max_num' => (int)($p['max_num'] ?? 0),
            'min_height' => (int)($p['min_height'] ?? 0),
            'max_height' => (int)($p['max_height'] ?? 0),
            'active_type' => is_array($p['active_type'] ?? null) ? implode(',', $p['active_type']) : ($p['active_type'] ?? ''),
            'cuoshi' => is_array($p['cuoshi'] ?? null) ? implode(',', $p['cuoshi']) : ($p['cuoshi'] ?? ''),
            'result' => $p['result'] ?? 'A',
        ]);
        $this->success('巡视记录保存成功', ['id' => $id]);
    }
}
