<?php
/**
 * 求购供应
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-08-15
 * Time: 16:23
 */

namespace app\admin\controller;


class FeedBack extends BaseController
{
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * 我的供应
     */
    public function mySupply()
    {
        $list = model('BuySupply')->alias('a')->join('User b', 'a.user_id=b.id', 'left')->join('Admins c', 'b.vip_code=c.vip_code', 'left')->order('a.status, a.create_time desc')->where('a.type', 1)->field('a.*, b.user_name, c.name sale_name')->select();
        $this->assign('list', $list);
        return $this->fetch('mySupply');
    }

    /**
     * 求购
     */
    public function myBuy()
    {
        $list = model('BuySupply')->alias('a')->join('User b', 'a.user_id=b.id', 'left')->join('Admins c', 'b.vip_code=c.vip_code', 'left')->order('a.status, a.create_time desc')->where('a.type', 2)->field('a.*, b.user_name, c.name sale_name')->select();
        $this->assign('list', $list);
        return $this->fetch('mySupply');
    }

    /**
     * 销售反馈
     */
    public function saleFeed()
    {
        $list = model('BuySupply')->alias('a')->join('User b', 'a.user_id=b.id', 'left')->join('Admins c', 'b.vip_code=c.vip_code', 'left')->order('a.status, a.create_time desc')->where('c.id', session(config('adminKey')))->field('a.*, b.user_name, c.name sale_name')->select();
        $this->assign('list', $list);
        return $this->fetch();
    }

    /**
     * 求购处理
     */
    public function solve()
    {
        $id = input('id');
        $bs = model('BuySupply')->where('id', $id)->find();
        if($bs){
            $r = $bs->save(['opera_id'=>session(config('adminKey')), 'status'=>1]);
            if($r){
                exit_json();
            }else{
                exit_json(-1, '操作失败');
            }
        }else{
           exit_json(-1, '订单不存在');
        }



    }
}