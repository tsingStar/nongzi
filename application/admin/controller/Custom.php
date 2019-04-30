<?php
/**
 * Created by PhpStorm.
 * User: tsing
 * Date: 2019/1/22
 * Time: 20:27
 */

namespace app\admin\controller;


class Custom extends BaseController
{
    protected function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 补录信息首页
     */
    public function index()
    {
        $param = input("get.");
        $model = model("UserAddInfo")->alias("a")->join("User b", "a.user_id=b.id")->join("Admins c", "a.agent_id=c.id");
        if (isset($param["telephone"]) && $param["telephone"] != "") {
            $model->where("c.telephone", $param["telephone"])->whereOr("c.name", "like", "%".$param['telephone']."%");
        }
        if(isset($param['status']) && $param['status'] != ""){
            switch ($param['status']){
                case 1:
                    $model->where("a.is_ok", 0);
                    break;
                case 2:
                    $model->where("a.is_ok", 1);
                    break;
                case 3 :
                    $model->where("a.is_ok", 2);
                    break;
                case 4:
                    $model->where("a.is_ok", 1)->where("is_comp", 1);
                    break;
                case 5:
                    $model->where("a.is_ok", 1)->where("is_comp", 0);
                    break;
                case 6:
                    $model->where("is_comp", 2);
                    break;
            }
        }
        $list = $model->field("a.*, b.register_src, b.user_name nick_name,b.create_time regist_time, b.spread_money, c.name admin_name")->order("a.create_time desc")->paginate(10, false, ["query"=>$param]);
        $this->assign("param", $param);
        $this->assign("list", $list);
        return $this->fetch();
    }

    /**
     * 拒绝通过
     */
    public function refuse()
    {
        $id = input("id");
        $reason = input("reason");
        $is_again = input("is_again");
        $ua = model("UserAddInfo")->where("id", $id)->find();
        if(!$ua){
            exit_json(-1, "记录不存在");
        }
        if($ua["is_ok"] != 0){
            exit_json(-1, "记录已处理");
        }
        $ua->save([
            'is_ok'=>2,
            'reason'=>$reason
        ]);
        model("User")->save(['is_again'=>$is_again, 'check_status'=>2], ["id"=>$ua["user_id"]]);
        $this->saveLog(session("admin_id"), $ua["agent_id"], $ua["user_id"], "审核拒绝通过", json_encode(input("post.")));
        exit_json();
    }

    /**
     * 审核通过
     */
    public function agree()
    {
        $id = input("id");
        $ua = model("UserAddInfo")->where("id", $id)->find();
        if(!$ua){
            exit_json(-1, "记录不存在");
        }
        if($ua["is_ok"] != 0){
            exit_json(-1, "记录已处理");
        }
        $ua->save([
            'is_ok'=>1
        ]);
        model("User")->save(['check_status'=>1], ["id"=>$ua["user_id"]]);
        $this->saveLog(session("admin_id"), $ua["agent_id"], $ua["user_id"], "审核通过", json_encode(input("post.")));
        exit_json();
    }

    /**
     * 结算
     */
    public function comp()
    {
        $id = input("id");
        $status = input("status");
        $ua = model("UserAddInfo")->where("id", $id)->find();
        if(!$ua){
            exit_json(-1, "记录不存在");
        }
        $agent = model("Admins")->where("id", $ua["agent_id"])->find();
        $user = model("User")->where("id", $ua["user_id"])->find();
        $user->save(["is_commission"=>1]);
        if($status == 1){
            //同意结算
            $ua->save([
                "is_comp"=>1
            ]);
            $agent->setInc("total_commission", $user["spread_money"]);
            $agent->setInc("able_commission", $user["spread_money"]);
            model("UserCommissionLog")->save([
                "commission_money"=>$user["spread_money"],
                "agent_id"=>$ua["agent_id"],
                "user_id"=>$ua["user_id"],
                "admin_id"=>session("admin_id"),
                "type"=>1
            ]);
            $this->saveLog(session("admin_id"), $ua["agent_id"], $ua["user_id"], "客户结算", json_encode(input("post.")));
            exit_json();
        }
        if($status == 2){
            //撤销结算
            $ua->save([
                "is_comp"=>2
            ]);
            $agent->setDec("total_commission", $user["spread_money"]);
            $agent->setDec("able_commission", $user["spread_money"]);
            model("UserCommissionLog")->save([
                "commission_money"=>-$user["spread_money"],
                "agent_id"=>$ua["agent_id"],
                "user_id"=>$ua["user_id"],
                "admin_id"=>session("admin_id"),
                "type"=>2
            ]);
            $this->saveLog(session("admin_id"), $ua["agent_id"], $ua["user_id"], "撤销客户核算", json_encode(input("post.")));
            exit_json();
        }
    }

    /**
     * 保存操作日志
     */
    function saveLog($admin_id, $agent_id, $user_id, $name, $data){
        model("CustomOperaLog")->data([
            "admin_id"=>$admin_id,
            "agent_id"=>$agent_id,
            "user_id"=>$user_id,
            "name"=>$name,
            "data"=>$data
        ])->isUpdate(false)->save();
    }
}