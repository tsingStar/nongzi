<?php
/**
 * 平台会员管理
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-07-03
 * Time: 10:17
 */

namespace app\admin\controller;


class Member extends BaseController
{
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * 会员列表
     */
    public function index()
    {

        $start_time = strtotime(input('mintime'));
        $end_time = strtotime(input('maxtime'));
        $uname = input('uname');
        $param = input("get.");
        $where = [];
        if ($start_time && !$end_time) {
            $where['a.create_time'] = ['egt', $start_time];
        } else if (!$start_time && $end_time) {
            $where['a.create_time'] = ['elt', $end_time];
        } else if ($start_time && $end_time) {
            $where['a.create_time'] = [
                ['egt', $start_time],
                ['elt', $end_time]
            ];
        }
        if(isset($param["agent_name"]) && $param["agent_name"]){
            $where["b.name"] = [
                "like",
                "%".$param["agent_name"]."%"
            ];
        }
        if ($uname) {
            $where['a.telephone|a.user_name'] = $uname;
        }
        $userList = model('User')->alias('a')->join('Admins b', 'a.vip_code=b.vip_code', 'left')->where($where)->field('a.*, b.name sale_name')->order('a.create_time desc')->select();
        $this->assign('list', $userList);
        $this->assign("param", $param);
        return $this->fetch();
    }

    /**
     * 分配
     */
    public function deliver()
    {
        if (request()->isAjax()) {
            $id = input('id');
            $vip_code = input('vip_code');
            $admin = model("Admins")->where("vip_code", $vip_code)->find();
            $res = model('User')->save(['vip_code' => $vip_code, "spread_money"=>$admin["person_money"]], ['id' => $id]);
            if ($res) {
                exit_json();
            } else {
                exit_json(-1, '操作失败');
            }
        }
        $list = model('Admins')->where('vip_code', 'neq', '')->column('name', 'vip_code');
        $this->assign('list', $list);
        return $this->fetch();

    }

    /**
     * 销售客户
     */
    public function saleList()
    {
        $start_time = strtotime(input('mintime'));
        $end_time = strtotime(input('maxtime'));
        $uname = input('uname');
        $where = ['b.id' => session(config('adminKey'))];
        if ($start_time && !$end_time) {
            $where['a.create_time'] = ['egt', $start_time];
        } else if (!$start_time && $end_time) {
            $where['a.create_time'] = ['elt', $end_time];
        } else if ($start_time && $end_time) {
            $where['a.create_time'] = [
                ['egt', $start_time],
                ['elt', $end_time]
            ];
        }
        if ($uname) {
            $where['a.telephone|a.user_name'] = $uname;
        }
        $userList = model('User')->alias('a')->join('Admins b', 'a.vip_code=b.vip_code', 'left')->where($where)->field('a.*, b.name sale_name')->order('a.create_time desc')->select();
        $this->assign('list', $userList);
        return $this->fetch();

    }

    /**
     * 添加客户
     */
    public function addMember()
    {
        if (request()->isAjax()) {
            $telephone = input('telephone');
            $user = model('User')->where('telephone', $telephone)->find();
            if ($user) {
                exit_json(-1, '当前手机号已注册');
            } else {
                $res = model('User')->save(['user_name' => input('user_name'), 'telephone' => $telephone]);
                if ($res) {
                    exit_json();
                } else {
                    exit_json(-1, '添加失败');
                }
            }
        } else {
            return $this->fetch();
        }
    }

    /**
     * 用户详情
     */
    public function userDetail()
    {
        $id = input('id');
        $user = model('User')->where('id', $id)->find();
        $user['sale_name'] = model('Admins')->where('vip_code', $user['vip_code'])->value('name');
        $user['address'] = model('UserAddress')->where('user_id', $id)->select();
        $this->assign('user', $user);
        return $this->fetch();
    }


    //TODO 待处理

    /**
     * 更改会员状态
     */
    public function changeStatus()
    {
        $id = input('id');
        $enable = input('enable');
        $res = model('user')->save(['status' => $enable], ['id' => $id]);
        if ($res) {
            exit_json(1, '更新成功');
        } else {
            exit_json(1, '更新失败');
        }
    }

    /**
     * 下载会员
     */
    public function downloadMember()
    {
        if (request()->isPost()) {
            $start_time = input('start_time');
            $end_time = input('end_time');
            $where['creattime'] = [
                ['egt', strtotime($start_time)],
                ['elt', strtotime($end_time . "+1 day")]
            ];
            $order_list = model('user')->where($where)->select();
            $this->assign('list', $order_list);
            $this->assign('filename', date('Y-m-d') . '.xls');
            return $this->fetch('excel');
        }
        return $this->fetch();
    }

    /**
     * 账户变动
     */
    public function accountList()
    {
        $where = [];
        $uname = input('uname');
        if ($uname) {
            $where['b.phone|b.username'] = $uname;
        }
        $list = model('money_log')->alias('a')->join('user b', 'a.user_id=b.id')->field('a.*, b.username, b.phone')->where($where)->order('create_time desc')->select();
        $this->assign('list', $list);
        return $this->fetch();
    }


}