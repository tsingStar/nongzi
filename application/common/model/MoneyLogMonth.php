<?php
/**
 * 月资金变动
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-06-29
 * Time: 09:57
 */

namespace app\common\model;


class MoneyLogMonth
{
    /**
     * 添加月日志记录
     */
    public static function addLog($user_id, $month, $money, $type){
        $res = db('money_log_month')->where(['user_id'=>$user_id, 'month'=>$month])->find();
        if($res){
            db('money_log_month')->where(['user_id'=>$user_id, 'month'=>$month])->setInc($type, $money);
        }else{
            db('money_log_month')->insert([
                'user_id'=>$user_id,
                'month'=>$month,
                $type=>$money
            ]);
        }
    }

    /**
     * 获取月消费总额
     */
    public static function getMonthMoney($month, $user_id)
    {
        $r = db('money_log_month')->where([
            'month'=>$month,
            'user_id'=>$user_id
        ])->find();
        return ['add'=>$r['add']?'￥ '.$r['add']:'￥ 0', 'dec'=>$r['dec']?'￥ '.$r['dec']:'￥ 0'];
    }

}