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
        $month_user_able = model("User")->where("vip_code", $this->agent["vip_code"])->where("check_status", 1)->where("create_time", "gt", strtotime(date("Y-m")))->count();

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
        $vip_code = model('Admins')->where('id', $this->agent['id'])->value('vip_code');
        $spread_url = __URL__."/index/Index/app?vip_code=";
        require_once VENDOR_PATH.'Qrcode/phpqrcode.php';
        $file_name = md5($this->agent['id']).'.png';
        is_dir(__UPLOAD__.'/qrcode') OR mkdir(__UPLOAD__.'/qrcode', 0777, true);
        $save_path = __UPLOAD__.'/qrcode/'.$file_name;
        \QRcode::png($spread_url.$vip_code, $save_path, 1, 50);
        $img_url = __URL__.'/upload/qrcode/'.$file_name;
        $this->assign("ewm", $img_url);
        return $this->fetch();
    }

    /**
     * 财务信息
     * @return mixed
     */
    public function finance()
    {
        $day = db("withdraw_day")->find();
        $this->assign("able_commission", $this->agent["able_commission"]);
        $this->assign("day", $day);
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
        if(request()->isAjax()){
            $page = input("page");
            $list = model("Order")->alias("a")->join("User b", "a.user_id=b.id")->where("b.vip_code", $this->agent["vip_code"])->where("a.pay_status", 1)->order("a.create_time desc")->limit($page*10, 10)->select();
            $data = [];
            foreach ($list as $item){
                $temp = [];
                $temp["order_no"] = $item["order_no"];
                $temp["total_money"] = $item["total_money"];
                $temp["create_time"] = $item["create_time"];
                $temp["is_comp"] = $item["is_comp"];
                $temp["commission"] = $this->getOrderCommission($item["order_no"]);
                $data[] = $temp;
            }
            $is_next = count($data) == 10 ? 1 : 0;
            exit_json(1, "", ["data"=>$data,"is_next"=>$is_next]);
        }
        return $this->fetch();
    }

    public function getOrderCommission($order_no)
    {
        $commission = 0;
        $list = model("OrderDet")->where("order_no", $order_no)->select();
        if(in_array(15, explode(',', $this->agent['role_id']))){
            if($this->agent['agent_cate'] == 1){
                $c_type = "agent_commission";
            }else{
                $c_type = "parttime_commission";
            }
        }else{
            $c_type = 'salesman_commission';
        }
        foreach ($list as $value) {
            $commission += $value["price"] * $value["num"] * $value["$c_type"] / 100;
        }
        return round($commission, 2);
    }

    /**
     * 获取推广客户佣金记录
     */
    public function spreadCustom()
    {
        $status = input("status");
        if(!isset($status)){
            $list = model("UserAddInfo")->where("agent_id", $this->agent["id"])->select();
            $this->assign("cu", 1);
        }else{
            $list = model("UserAddInfo")->where("agent_id", $this->agent["id"])->where("is_ok", 1)->where("is_comp", 0)->select();
            $this->assign("cu", 2);
        }
        $this->assign("is_ok", ["0"=>"未处理", "1"=>"审核通过", "2"=>"审核拒绝"]);
        $this->assign("is_comp", ["0"=>"未结算", "1"=>"已结算", "2"=>"撤销结算"]);
        $this->assign("list", $list);
        return $this->fetch();
    }

    /**
     * 客户订单佣金
     */
    public function spreadOrder()
    {
        
        return $this->fetch();
    }

    public function spreadOrderData()
    {
        $page = input("page");
        $status = input("status");
        $model = model("Order")->alias("a")->join("User b", "a.user_id=b.id")->where("b.vip_code", $this->agent["vip_code"]);
        switch ($status) {
            case 1:
                //全部
                break;
            case 2:
                //待结算
                $model->where("is_comp", 0)->where("pay_status", 1)->where("order_status", "in", [1,2,3]);
                break;
            case 3:
                //已结算
                $model->where("is_comp", 1);
                break;
            default:
                exit_json(-1, "参数错误");
        }
        $list = $model->order("a.create_time")->limit($page * 10, 10)->select();
        $is_next = count($list) == 10 ? 1 : 0;
        $data = [];
        $is_comp = [
            "0"=>"未结算",
            "1"=>"已结算",
            "2"=>"已撤销"
        ];
        foreach ($list as $value){
            $data[] = [
                "order_no"=>$value["order_no"],
                "order_status"=>config('order_status')[$value['order_status']],
                "is_comp"=>$is_comp[$value["is_comp"]]
            ];
        }
        exit_json(1, '请求成功', ["list" => $data, "is_next" => $is_next]);
        
    }

    /**
     * 申请提现
     */
    public function applyWithdraw()
    {
        $day = db("withdraw_day")->find();
        if(request()->isAjax()){
            $today = date("d");
            if($today<$day["start_day"] || $today>$day["end_day"]){
                exit_json(-1, "当前日期不能提现");
            }
            $data = input("post.");
            if(floatval($data["money"])<0.01 || floatval($data["money"])>$this->agent["able_commission"]){
                exit_json(-1, "提现金额不足");
            }
            if(!$data["user_name"]){
                exit_json(-1, "姓名不能为空");
            }
            if(!$data["bank_name"]){
                exit_json(-1, "开户行不能为空");
            }
            if(!$data["bank_card_no"]){
                exit_json(-1, "银行卡号不能为空");
            }
            if(!$data["remarks"]){
                exit_json(-1, "备注不能为空");
            }
            $data["agent_id"] = $this->agent["id"];
            $data["pre_money"] = $this->agent["able_commission"];
            $res = model("WithdrawLog")->allowField(true)->save($data);
            if($res){
                $this->agent->setDec("able_commission", $data["money"]);
                exit_json();
            }else{
                exit_json(-1, "提现申请失败");
            }

        }
        $this->assign("day", $day);
        $this->assign("agent", $this->agent);
        return $this->fetch();
    }

    /**
     * 提现明细
     */
    public function withdrawLog()
    {
        $list = model("WithdrawLog")->where("agent_id", $this->agent["id"])->order("create_time desc")->paginate(15);
        $this->assign("status", [0=>"待处理", "1"=>"已处理", "2"=>"已拒绝"]);
        $this->assign("list", $list);
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
                model("UserAddInfo")->isUpdate(false)->allowField(true)->data($data)->save();
                $user->save(['is_add_info'=>1, 'check_status'=>0]);
                exit_json();
            }
        }

        $user_id = input("user_id");
        $user = model("User")->where("id", $user_id)->find();
        $area_list = model("Area")->column("name", "id");
        $custom_area = model("Area")->where('parent_id', $this->agent["city_id"])->column("name", "id");
        $p = model("UserAddInfo")->where("user_id", $user_id)->order("create_time desc")->find();
        if($p){
            $user_area = explode(" ", $p["address"]);
            $p["a0"] = $user_area[0];
            $p["a1"] = $user_area[1];
            $p["a2"] = $user_area[2];
            $p["a3"] = $user_area[3];
        }
        $this->assign("ua", $p);
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