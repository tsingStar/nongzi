<?php
/**
 * 平台充值赠送
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-06-27
 * Time: 11:04
 */

namespace app\common\model;


use think\Model;

class ChargeAct extends Model
{
    protected function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
    }

    protected $autoWriteTimestamp = true;

    /**
     * 检验充值赠送合法性
     * @param $money
     * @param $given
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function checkLegal($money, $given)
    {
        $res = $this->where(['money'=>$money, 'given_money'=>$given, 'status'=>1])->find();
        if($res){
            return true;
        }else{
            return false;
        }
    }

}