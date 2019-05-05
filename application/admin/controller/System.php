<?php
/**
 * Created by PhpStorm.
 * User: tsing
 * Date: 2019/1/20
 * Time: 22:29
 */

namespace app\admin\controller;


class System extends BaseController
{
    protected function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 提现日期设置
     */
    public function withdrawDay()
    {
        if(request()->isPost()){
            db("withdraw_day")->delete(true);
            db("withdraw_day")->insert(input("post."));
            echo '<script>alert("保存成功")</script>';
        }
        $this->assign("item", db("withdraw_day")->find());
        return $this->fetch();
    }

    /**
     * 代理商添加记录
     */
    public function agentLog()
    {
        $list = model("AgentLog")->alias("a")->join("Admins b", "a.admin_id=b.id")->field("a.*, b.name admin_name")->order('a.create_time desc')->paginate(15);
        $this->assign("list", $list);
        return $this->fetch();
    }

    /**
     * 客户审核日志
     */
    public function customOperaLog()
    {
        $list = model("CustomOperaLog")->alias("a")->join("Admins b", "a.admin_id=b.id")->join("Admins c", "a.agent_id=c.id")->join("User d", "a.user_id=d.id")->field("a.*, b.name admin_name, c.name agent_name, d.user_name")->order('a.create_time desc')->paginate(15);
        $this->assign("list", $list);
        return $this->fetch();
    }

    /**
     * 商品佣金变动记录
     */
    public function productCommission()
    {
        $list = model("ProductCommissionChangeLog")->alias("a")->join("Admins b", "a.admin_id=b.id")->join("Product c", "a.product_id=c.id")->field("a.*, b.name admin_name, c.name product_name")->order('a.create_time desc')->paginate(15);
        $this->assign("list", $list);
        return $this->fetch();
    }

    /**
     * 订单审核日志
     */
    public function checkOrderLog()
    {
        $list = model("OrderCommissionOperaLog")->alias("a")->join("Admins b","a.admin_id=b.id")->field("a.*, b.name admin_name")->order('a.create_time desc')->paginate(15);
        $this->assign("list", $list);
        return $this->fetch();
    }

}