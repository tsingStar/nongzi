<?php
/**
 * Created by PhpStorm.
 * User: tsing
 * Date: 2019/1/24
 * Time: 14:14
 */

namespace app\admin\controller;


class Withdraw extends BaseController
{
    protected function _initialize()
    {
        parent::_initialize();
    }

    public function withdrawLog()
    {
        $model = model("WithdrawLog")->alias("a")->join("Admins b", "a.agent_id=b.id");
        $list = $model->field("a.*, b.name agent_name")->paginate(15);
        $this->assign("list", $list);
        $this->assign("status", [0=>"未处理", 1=>"已处理", 2=>"已拒绝"]);
        return $this->fetch();
    }

    /**
     * 提现详情
     */
    public function drawDetail()
    {
        $id = input("id");
        if(request()->isAjax()){
            $withdraw = model("WithdrawLog")->where("id", $id)->find();
            $image = input("image");
            $pay_money = input("pay_money");
            $beizhu = input("beizhu");
            $status = 1;
            if($image == ""){
                exit_json(-1, "支付凭证不能为空");
            }
            if(floatval($pay_money)>$withdraw["money"] || floatval($pay_money)<=0){
                exit_json(-1,"支付金额不能大于提现金额或金额错误");
            }
            $res = $withdraw->save([
                "image"=>$image,
                "pay_money"=>$pay_money,
                "beizhu"=>$beizhu,
                "status"=>$status
            ]);
            if($res){
                exit_json();
            }else{
                exit_json(-1, "处理失败");
            }


        }
        $item = model("WithdrawLog")->alias("a")->join("Admins b", "a.agent_id=b.id")->where("a.id", $id)->field("a.*, b.name agent_name")->find();
        $this->assign("item", $item);
        return $this->fetch();
    }

    public function refuse_commission()
    {
        $id = input("id");
        $reason = input("reason");
        $wd = model("WithdrawLog")->where("id", $id)->find();
        if($wd['status'] !=0 ){
            exit_json(-1, "申请已处理");
        }
        $res = $wd->save([
            "status"=>2,
            "beizhu"=>$reason
        ]);
        if($res){
            exit_json();
        }else{
            exit_json(-1, "处理失败");
        }
        
    }

    /**
     * 用户推广记录
     */
    public function userCommissionLog()
    {
        $model = model("UserCommissionLog")->alias("a")->join("Admins b", "a.agent_id=b.id");
        $list = $model->field("a.*, b.name agent_name")->order("a.create_time desc")->paginate(15);
        $this->assign("list", $list);
        $this->assign("type", [1=>'用户推广结算', 2=>"用户推广结算撤销"]);
        return $this->fetch();
    }

    /**
     * 订单佣金记录
     */
    public function orderCommissionLog()
    {
        $model = model("OrderCommissionLog")->alias("a")->join("Admins b", "a.agent_id=b.id");

        $list = $model->field("a.*, b.name agent_name")->order('a.create_time desc')->paginate(15);
        $this->assign("list", $list);
        $this->assign("type", [1=>"订单返现结算", 2=>"订单返现撤销", 3=>"首单返现", 4=>"首单返现撤销"]);
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