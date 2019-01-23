<?php
/**
 * Created by PhpStorm.
 * User: tsing
 * Date: 2019/1/18
 * Time: 18:01
 */

namespace app\agent\controller;


class Index extends BaseAgent
{
    protected function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {

        $user_able = model("User")->where("vip_code", $this->agent["vip_code"])->where("is_commission", 1)->count();
        $order_total = model("Order")->alias("a")->join("User c", "a.user_id=c.id")->where("c.vip_code", $this->agent["vip_code"])->where("a.pay_status", 1)->count();
        $comp_order = model("Order")->alias("a")->join("User c", "a.user_id=c.id")->where("c.vip_code", $this->agent["vip_code"])->where("a.pay_status", 1)->where("a.is_comp", 1)->count();

        //当月订单有效数量
        $month_total_order = model("Order")->alias("a")->join("User c", "a.user_id=c.id")->where("c.vip_code", $this->agent["vip_code"])->where("a.pay_status", 1)->where("a.create_time", "gt", strtotime(date("Y-m")))->count();
        $month_comp_order = model("Order")->alias("a")->join("User c", "a.user_id=c.id")->where("c.vip_code", $this->agent["vip_code"])->where("a.pay_status", 1)->where("a.is_comp", 1)->where("a.create_time", "gt", strtotime(date("Y-m")))->count();
        //订单佣金
        $commission_money = model("OrderCommissionLog")->where("agent_id", $this->agent["id"])->where("create_time", "gt", strtotime(date("Y-m")))->sum("commission_money");

        //当月用户数量
        $month_user = model("User")->where("vip_code", $this->agent["vip_code"])->where("create_time", "gt", strtotime(date("Y-m")))->count();
        $month_user_able = model("User")->where("vip_code", $this->agent["vip_code"])->where("check_status", 1)->where("create_time", "gt", date("Y-m"))->count();

        $commission_user = model("UserCommissionLog")->where("agent_id", $this->agent["id"])->where("create_time", "gt", strtotime(date("Y-m")))->sum("commission_money");

        $data = [
            "user_num" => $user_able,
            "order_total" => $order_total,
            "comp_order" => $comp_order,
            "month_total_order" => $month_total_order,
            "month_comp_order" => $month_comp_order,
            "commission_money" => $commission_money,
            "month_user" => $month_user,
            "month_user_able" => $month_user_able,
            "commission_user" => $commission_user
        ];
        $this->assign("item", $data);
        $this->assign("agent", $this->agent);
        return $this->fetch();
    }

    /**
     * 推广二维码
     * @return mixed
     */
    public function code()
    {
        exit("正在维护");
        return $this->fetch();
    }

    /**
     * 财务信息
     * @return mixed
     */
    public function finance()
    {

        exit("正在维护");
        return $this->fetch();
    }

    /**
     * 客户列表
     */
    public function custom()
    {
        return $this->fetch();
    }

    /**
     * 客户数据
     */
    public function customData()
    {
        $page = input("page");
        $status = input("status");
        $model = model("User");
        switch ($status) {
            case 1:
                //全部
                break;
            case 2:
                //补录
                $model->where("is_add_info", 0);
                break;
            case 3:
                //未通过
                $model->where("is_add_info", 1)->where("check_status", 2);
                break;
            case 4:
                //未结算
                $model->where("is_add_info", 1)->where("check_status", 1)->where("is_commission", 0);
                break;
            default:
                exit_json(-1, "参数错误");
        }
        $list = $model->where("vip_code", $this->agent["vip_code"])->limit($page * 10, 10)->select();
        $is_next = count($list) == 10 ? 1 : 0;
        exit_json(1, '请求成功', ["list" => $list, "is_next" => $is_next]);
    }

    /**
     * 客户订单列表
     */
    public function customOrder()
    {

        exit("正在维护");
        return $this->fetch();

    }

    /**
     * 获取推广客户佣金记录
     */
    public function spreadCustom()
    {
        exit("正在维护");
        return $this->fetch();
    }

    /**
     * 客户订单佣金
     */
    public function spreadOrder()
    {
        exit("正在维护");
        return $this->fetch();
    }

    /**
     * 申请提现
     */
    public function applyWithdraw()
    {
        exit("正在维护");
        return $this->fetch();
    }

    /**
     * 完善信息
     */
    public function saveDetail()
    {
        if(request()->isAjax()){
            $data = input("post.");
            $data["agent_id"] = $this->agent['id'];
            $r = model("UserAddInfo")->where("user_id", $data["user_id"])->where("agent_id", $data["agent_id"])->where("is_ok", "eq", 0)->find();
            $user = model("User")->where("id", $data["user_id"])->find();
            if($r){
                exit_json(-1, "你已完善此客户信息");
            }else{
                model("UserAddInfo")->isUpdate(false)->data($data)->save();
                $user->save(['is_add_info'=>1, 'check_status'=>0]);
                exit_json();
            }
        }

        $user_id = input("user_id");
        $user = model("User")->where("id", $user_id)->find();
        $area_list = model("Area")->column("name", "id");
        $custom_area = model("Area")->where('parent_id', $this->agent["city_id"])->column("name", "id");
        $this->assign("agent", $this->agent);
        $this->assign("custom_area", $custom_area);
        $this->assign("user", $user);
        $this->assign("area_list", $area_list);
        $this->assign("user_id", $user_id);
        return $this->fetch();

    }

    /**
     * 信息审核
     */
    public function checkDetail()
    {
        $user_id = input("user_id");
        $user = model("User")->where("id", $user_id)->find();
        $add_info = model("UserAddInfo")->where("user_id", $user_id)->where("agent_id", $this->agent["id"])->order("create_time desc")->find();
        $this->assign("user", $user);
        $this->assign("user_info", $add_info);
        return $this->fetch();

    }

    /**
     * 上传文件
     */
    public function uploadFile()
    {
        $file = request()->file("file");
        if ($file) {
            $hash = $file->hash();
            $info = $file->move(__UPLOAD__);
            $saveName = $info->getSaveName();
            $path = __URL__ . "/upload/" . $saveName;
            $res = true;
        } else {
            $res = false;
        }
        if ($res) {
            exit_json(1, '', $path);
        } else {
            exit_json(-1, "图片上传失败");
        }
    }

}