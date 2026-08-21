<?php

namespace app\appapi\controller\check;

use app\common\controller\App;
use think\facade\Db;

class Plan extends App{
    public function index(){
        $params = $this->request->param();
        $query = Db::name('check_plan')->alias('p')
            ->leftJoin('car c', 'c.id = p.car_id')
            ->field('p.id,p.date,p.admin_id,p.weather,p.car_id,c.name as car_name,c.code as car_code')
            ->order('p.date desc,p.id desc');

        if (!empty($params['date'])) $query->where('p.date', $params['date']);
        if (!empty($params['start_date'])) $query->where('p.date', '>=', $params['start_date']);
        if (!empty($params['end_date'])) $query->where('p.date', '<=', $params['end_date']);
        if (!empty($params['car_id'])) $query->where('p.car_id', (int)$params['car_id']);
        if (!empty($params['admin_id'])) $query->whereRaw('FIND_IN_SET(?, p.admin_id)', [(int)$params['admin_id']]);

        $plans = $query->select()->toArray();
        foreach ($plans as &$plan) {
            $adminIds = array_filter(explode(',', $plan['admin_id']));
            $plan['inspectors'] = $adminIds ? Db::name('admin')->whereIn('id', $adminIds)->column('nickname') : [];
            $plan['car'] = trim($plan['car_name'] . ' ' . $plan['car_code']);
            $plan['location'] = '场内';
            $plan['records'] = Db::name('check_log')->alias('l')
                ->leftJoin('bird b', 'b.id = l.bird_id')
                ->where('l.check_plan_id', $plan['id'])
                ->field('l.id,l.time,l.area,l.bird_id,b.name as species,l.min_num as minCount,l.max_num as maxCount,l.min_height as minHeight,l.max_height as maxHeight,l.active_type,l.cuoshi,l.result')
                ->order('l.id desc')->select()->toArray();
            unset($plan['admin_id'], $plan['car_name'], $plan['car_code']);
        }
        $this->success('', $plans);
    }

    public function add(){
        $params = $this->request->post();
        $adminIds = $params['admin_ids'] ?? [];
        if (!is_array($adminIds)) $adminIds = explode(',', $adminIds);
        $adminIds = array_values(array_filter(array_map('intval', $adminIds)));
        $date = $params['date'] ?? '';
        $weather = $params['weather'] ?? '';
        $carId = (int)($params['car_id'] ?? 0);
        if (!$date || !$weather || !$carId || !$adminIds) $this->error('请完整填写巡视计划');
        if (!Db::name('car')->where('id', $carId)->find()) $this->error('车辆不存在');

        $id = Db::name('check_plan')->insertGetId([
            'date' => $date,
            'admin_id' => implode(',', $adminIds),
            'weather' => $weather,
            'car_id' => $carId,
        ]);
        $this->success('巡视计划提交成功', ['id' => $id]);
    }
}
