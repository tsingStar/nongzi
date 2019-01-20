<?php
/**
 * 统计记录
 * Created by PhpStorm.
 * User: tsing
 * Date: 2019/1/7
 * Time: 20:18
 */

namespace app\app\controller;


use think\Controller;

class Statistic extends Controller
{
    protected function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 启动统计
     */
    public function launchCount()
    {
        //登录终端 1 安卓 2 ios 3 小程序
        $type = input("type");
        $date = date("Y-m-d");
        model("LaunchData")->save([
            "type"=>$type,
            "launch_date"=>$date
        ]);
        exit_json();
    }

    /**
     * 客服统计
     */
    public function customService()
    {
        $user_id = input("user_id");
        model("CustomService")->save([
            "user_id"=>$user_id,
            "create_time"=>time(),
            "update_time"=>time()
        ]);
        exit_json();
    }

    /**
     * 供求统计
     */
    public function supplyCount()
    {
        $user_id = input("user_id");
        model("SupplyLog")->save([
            "user_id"=>$user_id,
            "create_time"=>time(),
            "update_time"=>time()
        ]);
        exit_json();
    }


}