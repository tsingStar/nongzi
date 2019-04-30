<?php
/**
 * 平台订单
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-07-07
 * Time: 15:21
 */

namespace app\admin\controller;


use app\common\model\SendSms;
use app\common\model\WeiXinPay;
use think\Log;

class Order extends BaseController
{
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * 线下打款
     */
    public function addPayInfo()
    {
        $order_no = input("order_no");
        $order = model("Order")->where("order_no", $order_no)->find();
        if (request()->isAjax()) {
            $num = model("Order")->where("user_id", $order["user_id"])->count();
            $is_first = $num == 1 ? 1 : 0;
            $order->save(['out_transaction_id' => input("pay_no"), 'pay_type' => 4, 'pay_status' => 1, 'pay_time' => date("Y-m-d H:i:s"), 'order_status' => 1, 'order_no_pre' => input("pay_no"), "is_first" => $is_first]);
            $order_det = model('OrderDet')->where('order_no', $order_no)->select();
            foreach ($order_det as $item) {
                model('ProductAttr')->where('product_id', $item['product_id'])->where('prop_value_attr', $item['prop_value_attr'])->setDec('remain', $item['num']);
                model('Product')->where('id', $item['product_id'])->setInc('sell_num', $item['num']);
            }

            exit_json();
        }
        $this->assign("item", $order);
        return $this->fetch("addPayInfo");
    }


    /**
     * 全部订单
     */
    public function orderList()
    {
        $this->assign('is_admin', 1);
        $param = input("get.");
        $order = model('order')->alias("a")->join('user b', 'a.user_id=b.id', 'left')->join("Admins c", "b.vip_code=c.vip_code", 'left');
        if ((isset($param["searchKey"]) && $param["searchKey"] != "") && (isset($param["searchValue"]) && $param["searchValue"] != "")) {
            $order->where($param["searchKey"], 'like', "%" . $param["searchValue"] . "%");
        }
        if (isset($param["order_status"]) && $param["order_status"] !== "") {
            $order->where("a.order_status", $param["order_status"]);
        }
        if (isset($param["start_time"]) && $param["start_time"] != "") {
            $order->where("a.create_time", "egt", strtotime($param["start_time"]));
        }
        if (isset($param["end_time"]) && $param["end_time"] != "") {
            $order->where("a.create_time", "elt", strtotime($param["end_time"]) + 86400);
        }
        if (isset($param["agent_name"]) && $param["agent_name"] != "") {
            $order->where("c.name", "like", "%" . $param["agent_name"] . "%");
        }

//        if (request()->isPost()) {
//            $order = model('order');
//            $where = [];
//            if (input('searchKey') && input('searchValue')) {
//                $where[input('searchKey')] = input('searchValue');
//            }
//
//            if (input('order_status') !== "") {
//                $where['a.order_status'] = input('order_status');
//            }
//            if (input('start_time')) {
//                $where['a.create_time'] = ['egt', strtotime(input('start_time'))];
//            }
//            if (input('end_time')) {
//                $where['a.create_time'] = ['elt', strtotime(input('end_time') . '+1 day')];
//            }
//            if (input('start_time') && input('end_time')) {
//                $where['a.create_time'] = [
//                    ['egt', strtotime(input('start_time'))],
//                    ['elt', strtotime(input('end_time') . '+1 day')]
//                ];
//            }
        $order->where("is_trash", 0);
        $order_list = $order->field('a.*, b.user_name, b.telephone, c.name agent_name')->order("a.create_time desc")->paginate(20, false, ["query" => $param]);
        $this->assign('list', $order_list);
        $this->assign("param", $param);
        return $this->fetch('orderList');
//        }
//        $this->assign('list', []);
//        return $this->fetch();
    }

    /**
     * 销售客服订单
     */
    public function salerOrderList()
    {
        $vip_code = model('Admins')->where('id', session(config('adminKey')))->value('vip_code');
        $user_ids = model('User')->where('vip_code', $vip_code)->column('id');
//        $where = ['a.user_id' => ['in', $user_ids]];
//        if (input('searchKey') && input('searchValue')) {
//            $where[input('searchKey')] = input('searchValue');
//        }
//        if (input('order_status') !== "") {
//            $where['a.order_status'] = input('order_status');
//        }
//        if (input('start_time')) {
//            $where['a.create_time'] = ['egt', strtotime(input('start_time'))];
//        }
//        if (input('end_time')) {
//            $where['a.create_time'] = ['elt', strtotime(input('end_time') . '+1 day')];
//        }
//        if (input('start_time') && input('end_time')) {
//            $where['a.create_time'] = [
//                ['egt', strtotime(input('start_time'))],
//                ['elt', strtotime(input('end_time') . '+1 day')]
//            ];
//        }

        $param = input("get.");
        $order = model('order')->alias("a")->join('user b', 'a.user_id=b.id')->where("a.user_id", "in", $user_ids)->join("Admins c", "c.vip_code=b.vip_code");
        if ((isset($param["searchKey"]) && $param["searchKey"] != "") && (isset($param["searchValue"]) && $param["searchValue"] != "")) {
            $order->where($param["searchKey"], $param["searchValue"]);
        }
        if (isset($param["order_status"]) && $param["order_status"] !== "") {
            $order->where("a.order_status", $param["order_status"]);
        }
        if (isset($param["start_time"]) && $param["start_time"] != "") {
            $order->where("a.create_time", "egt", strtotime($param["start_time"]));
        }
        if (isset($param["end_time"]) && $param["end_time"] != "") {
            $order->where("a.create_time", "elt", strtotime($param["end_time"]) + 86400);
        }
        $order->where("is_trash", 0);

        $orderList = $order->field('a.*, b.user_name, b.telephone, c.name agent_name')->order("a.create_time desc")->paginate(20, false, ["query" => $param]);
        $this->assign('list', $orderList);
        $this->assign('is_show', 1);
        $this->assign("param", $param);
        return $this->fetch('orderList');
    }

    /**
     * 回收订单
     */
    public function order_trash()
    {
        $order_no = input("order_no");
        $status = input("status");
        $order = model("Order")->where("order_no", $order_no)->find();
        if ($order) {
            if ($order->save(["is_trash" => $status])) {
                exit_json();
            } else {
                exit_json(-1, "操作失败");
            }
        } else {
            exit_json(-1, "订单不存在");
        }
    }

    public function trashOrderList()
    {
        $this->assign('is_admin', 1);
        $param = input("get.");
        $order = model('order')->alias("a")->join('user b', 'a.user_id=b.id')->join("Admins c", "b.vip_code=c.vip_code");
        if ((isset($param["searchKey"]) && $param["searchKey"] != "") && (isset($param["searchValue"]) && $param["searchValue"] != "")) {
            $order->where($param["searchKey"], $param["searchValue"]);
        }
        if (isset($param["order_status"]) && $param["order_status"] !== "") {
            $order->where("a.order_status", $param["order_status"]);
        }
        if (isset($param["start_time"]) && $param["start_time"] != "") {
            $order->where("a.create_time", "egt", strtotime($param["start_time"]));
        }
        if (isset($param["end_time"]) && $param["end_time"] != "") {
            $order->where("a.create_time", "elt", strtotime($param["end_time"]) + 86400);
        }
        $order->where("is_trash", 1);
        $order_list = $order->field('a.*, b.user_name, b.telephone, c.name agent_name')->order('a.create_time desc')->paginate(20, false, ["query" => $param]);
        $this->assign('list', $order_list);
        $this->assign("param", $param);
        return $this->fetch('orderList');
    }

    /**
     * 订单配送，订单详情
     */
    public function orderSend()
    {
        $order_det = model('OrderDet')->where('order_no', input('order_no'))->select();
        $this->assign('list', $order_det);
        $this->assign('order_no', input('order_no'));
        return $this->fetch();
    }

    /**
     * 确认订单发货
     */
    public function sureSend()
    {
        $order_no = input('order_no');
        $order = model('Order')->where('order_no', $order_no)->find();
        if ($order) {
            if ($order['order_status'] == 1) {
                $res = $order->save(['order_status' => 2, 'send_time' => date('Y-m-d H:i'), 'is_send' => 1]);
                if ($res) {
                    $order_no = strlen($order_no)>20?(substr($order_no, 0, 8)."****".substr($order_no, -8)):$order_no;
                    $content = urlencode($order_no);
                    $send = new SendSms($order['receiver_telephone'], config('sms.sendId'), $content);
                    $res = $send->sendVcode();
                    if ($res) {
                        model('SmsLog')->save(['telephone' => $order['receiver_telephone'], 'type' => 1002]);
                    }
                    exit_json();
                } else {
                    exit_json(-1, '操作失败');
                }
            } else {
                exit_json(-1, '订单状态异常');
            }
        } else {
            exit_json(-1, '订单不存在');
        }
    }

    public function send_sure_msg()
    {
        $order_no = input("order_no");
        $order = model('Order')->where('order_no', $order_no)->find();
        if ($order) {
            $l = model("SmsLog")->where("code", $order['id'])->where("type", 1003)->find();
            if ($l) {
                exit_json(-1, "已提醒过");
            }
            $order_no = strlen($order_no)>20?(substr($order_no, 0, 8)."****".substr($order_no, -8)):$order_no;
            $content = urlencode($order_no);
            $send = new SendSms($order['receiver_telephone'], config('sms.sureId'), $content);
            $res = $send->sendVcode();
            if ($res) {
                model('SmsLog')->save(['telephone' => $order['receiver_telephone'], 'type' => 1003, 'code' => $order['id']]);
                exit_json();
            }
        } else {
            exit_json(-1, '订单不存在');
        }
    }

    /**
     * 订单详情
     */
    public function order_detail()
    {
        $order_id = input('order_id');
        $order = model('order');
        $item = $order->alias('a')->join('user b', 'a.user_id=b.id')->field('a.*, b.user_name, b.telephone')->where('a.id', $order_id)->find();
//        $pay_time = $item['pay_time']?date();
        $order_det = model('order_det');
        $good_list = $order_det->where('order_no', $item['order_no'])->select();
        $this->assign('item', $item);
        $this->assign('good_list', $good_list);
        return $this->fetch();
    }

    /**
     * 运费调整
     */
    public function sendFeeChange()
    {
        $order_id = input('order_id');
        $order = model('Order')->where('id', $order_id)->find();
        if ($order) {
            if (request()->isAjax()) {
                if ($order['order_status'] != 0) {
                    exit_json(-1, '订单状态错误');
                }
//                $send_fee = input('send_fee');
//                $discount_fee = input("discount_fee");

                $order_det = input("order_det/a");
                $discount_fee = 0;
                $send_fee = 0;
                foreach ($order_det['det_id'] as $key => $val) {
                    //重置运费和优惠
                    $odt = model("OrderDet")->find($val);
                    $odt->send_fee = $order_det["send_fee"][$key];
                    $send_fee += $order_det["send_fee"][$key];
                    $odt->discount = $order_det["discount_fee"][$key];
                    $discount_fee += $order_det["discount_fee"][$key];
                    $odt->save();
                }
                $res = $order->save([
                    'send_fee' => $send_fee,
                    'discount_fee' => $discount_fee,
                    'order_money' => $order['total_money'] + $send_fee - $discount_fee
                ]);
                if ($res) {
                    exit_json();
                } else {
                    exit_json(-1, '修改失败,刷新重试');
                }
            } else {
                $product_list = model("OrderDet")->where("order_no", $order["order_no"])->select();
                $this->assign('order', $order);
                $this->assign("product_list", $product_list);
                return $this->fetch();
            }
        } else {
            exit_json(-1, '订单不存在');
        }
    }

    /**
     * 订单退款
     */
    public function orderRefund()
    {
        $order_no = input('order_no');
        $order_refund = model('OrderRefund')->where('order_no', $order_no)->find();
        if (request()->isAjax()) {
            $data = input('post.');
            $data['status'] = 1;
            $res = $order_refund->save($data);
            if ($res) {
                exit_json();
            } else {
                exit_json(-1, '操作失败');
            }
        }
        $list = model('OrderDet')->whereIn('id', $order_refund['refund_detail'])->select();
        $this->assign('order_refund', $order_refund);
        $this->assign('list', $list);
        return $this->fetch();
    }

    /**
     * 退款收获
     */
    public function getOrder()
    {
        $refund_id = input('refund_id');
        $order = model('OrderRefund')->where('id', $refund_id)->find();
        if ($order) {
            if ($order->save(['is_get' => 1])) {
                exit_json();
            } else {
                exit_json(-1, '操作失败');
            }
        } else {
            exit_json(-1, '操作失败');
        }
    }

    /**
     * 财务退款列表
     */
    public function refundMoney()
    {
        $where = ['a.is_get' => 1];
        if (input('status')) {
            $where['a.status'] = input('status');
        }
        $list = model('OrderRefund')->alias('a')->join('Order b', 'a.order_no=b.order_no')->where($where)->field('a.*, b.pay_type, b.order_money, b.refund_money')->order("a.create_time desc")->paginate(20);
        $this->assign("render", $list->render());
        foreach ($list as $item) {
            $od = model('OrderDet')->whereIn('id', $item['refund_detail'])->select();
            $item['refund_detail'] = $od;
        }
        $this->assign('list', $list);
        return $this->fetch();
    }

    /**
     * 直接退款
     */
    public function backMoney()
    {
        $refund_id = input('id');
        $refund = model('OrderRefund')->where('id', $refund_id)->find();
        if ($refund['status'] != 1 || $refund['is_get'] != 1) {
            exit_json(-1, '订单状态错误');
        } else {
            if ($refund['refund_type'] == 1) {

                //整单退
                $order = model('Order')->where('order_no', $refund['order_no'])->find();
                //退款参数
                $orderInfo = [
                    'trade_no' => $order['out_transaction_id'],
                    'refund_id' => $refund['id'],
                    'total_money' => $order['order_money'],
                    'refund_money' => $order['order_money'],
                    'shop_id' => session(config('adminKey')),
                    'pay_status' => $order['pay_type']
                ];
                $weixin = new WeiXinPay();
                $res = $weixin->refund($orderInfo);
                if ($res['code']) {
                    $order->save(['refund_money' => $refund_money]);
                    $refund->save(['status' => 2, 'image' => $image, 'pay_time' => $pay_time]);
                    exit_json(1, $res['msg']);
                } else {
//                    exit_json(-1, $res['msg']);
                    exit_json(-1, "退款失败");
                }
            } else {
                exit_json(-1, ' 参数错误');
            }
        }
    }

    public function backDetail()
    {
        $refund_id = input('refund_id');
        $refund = model('OrderRefund')->where('id', $refund_id)->find();
        $refund['refund_money'] = model('Order')->where('order_no', $refund['order_no'])->value("refund_money");
        $this->assign('item', $refund);
        return $this->fetch();
    }

    /**
     * 增加退款附加信息
     */
    public function addRefundInfo()
    {
        $refund_id = input('refund_id');
        $refund = model('OrderRefund')->where('id', $refund_id)->find();
        if (request()->isAjax()) {
            if ($refund['status'] != 1) {
                exit_json(-1, '退款已处理,无需重复处理');
            }
            $image = input('image');
            $pay_time = input('pay_time');
            $order = model('Order')->where('order_no', $refund['order_no'])->find();
            $refund_money = input('refund_money');
            if ($refund_money > $order['order_money']) {
                exit_json(-1, ' 退款金额不能大于订单总金额');
            }
            if ($refund['refund_type'] == 1) {
                //if ($refund_money > $order['order_money']) {
                //    exit_json(-1, ' 退款金额不能大于订单总金额');
                //}
                //整单退
                //退款参数
                if ($refund['refund_content'] == 1) {
                    $refund_money = $order['order_money'];
                } else {
                    $refund_money = $order['total_money'];
                }
                $orderInfo = [
                    'trade_no' => $order['out_transaction_id'],
                    'refund_id' => $refund['id'],
                    'total_money' => $order['order_money'],
                    'refund_money' => $refund_money,
                    'shop_id' => session(config('adminKey')),
                    'pay_status' => $order['pay_type']
                ];
                $weixin = new WeiXinPay();
                $res = $weixin->refund($orderInfo);
                if ($res['code']) {
                    $order->save(['refund_money' => $refund_money, "is_apply_refund" => 2]);
                    $refund->save(['status' => 2, 'image' => $image, 'pay_time' => $pay_time]);
                    exit_json(1, $res['msg']);
                } else {
                    exit_json(-1, $res['msg']);
//                    exit_json(-1, "退款失败");
                }
            } else {
                //$money = model('OrderDet')->whereIn('id', $refund['refund_detail'])->sum('num*price');
                //if ($refund_money > $money) {
                //    exit_json(-1, ' 退款金额不能大于申请退货商品总金额');
                //}
                $refund->save(['image' => $image, 'pay_time' => $pay_time, 'status' => 2]);
                $order->save(['refund_money' => $refund_money, "is_apply_refund" => 2]);
                exit_json();
            }
        } else {
            $this->assign('item', $refund);
            return $this->fetch();
        }
    }


    public function ok_refund()
    {
        $order_no = input("order_no");
        $money = input("money");
        $order = model('Order')->where('order_no', $order_no)->find();
        $refund = model('OrderRefund')->where('order_no', $order_no)->find();
        $res1 = $order->save(["refund_money" => $money, "is_apply_refund" => 2]);
        $res2 = $refund->save([
            "pay_time" => date("Y-m-d"),
            "status" => 2
        ]);
        if ($res1 && $res2) {
            exit_json();
        } else {
            exit_json(-1, '处理失败');
        }

    }

    /**
     * 取消订单
     */
    public function order_refuse()
    {
        $order_no = input('order_no');
        $order = model('Order')->where('order_no', $order_no)->find();
        if ($order['is_send'] == 1 && $order['sure_time'] == "") {
            $res1 = $order->save(['order_status' => 2, 'is_apply_refund' => 0]);
        } else if ($order['is_send'] == 1 && $order['sure_time'] != "") {
            $res1 = $order->save(['order_status' => 3, 'is_apply_refund' => 0]);
        } else {
            $res1 = $order->save(['order_status' => 1, 'is_apply_refund' => 0]);
        }
        $res2 = model('OrderRefund')->where('order_no', $order_no)->delete();
        if ($res1 && $res2) {
            exit_json();
        } else {
            exit_json(-1, '处理失败');
        }
    }

    /**
     * 订单核算
     */
    public function order_check()
    {
        $status = input("status");
        $order_no = input("order_no");
        $order = model("Order")->where("order_no", $order_no)->find();
        $commission = $this->getOrderCommission($order_no);
        if ($status == 1) {
            if ($order["is_comp"] != 0) {
                exit_json(-1, "订单已处理过");
            }
            $this->addCheckLog(session("admin_id"), $order_no, 1, "订单审核通过", $commission);
            $this->addOrderCommissionLog(session("admin_id"), 1, $order_no, $order["user_id"], $commission);
            $order->save(["is_comp" => 1]);
            exit_json();
        } elseif ($status == 2) {
            if ($order["is_comp"] != 0) {
                exit_json(-1, "订单已处理过");
            }
            $this->addCheckLog(session("admin_id"), $order_no, 2, "订单审核拒绝", $commission);
            $order->save(["is_comp" => 2]);
            exit_json();
        } elseif ($status == 3) {
            if ($order["is_comp"] != 1) {
                exit_json(-1, "订单状态错误");
            }
            $this->addCheckLog(session("admin_id"), $order_no, 3, "订单审核撤销", $commission);
            $this->addOrderCommissionLog(session("admin_id"), 2, $order_no, $order["user_id"], -$commission);
            $order->save(["is_comp" => 2]);
            exit_json();
        } else {
            exit_json(-1, "参数错误");
        }

    }

    /**
     * 首单核算
     */
    public function order_first()
    {
        $status = input("status");
        $order_no = input("order_no");
        $order = model("Order")->where("order_no", $order_no)->find();
        if ($order["is_first"] != 1) {
            exit_json(-1, "订单不是首单");
        }
//        $vip_code = model("User")->where("id", $order['user_id'])->value("vip_code");
//        $agent = model("Admins")->where("vip_code", $vip_code)->find();
        $commission = $order["first_money"];
        if ($status == 1) {
            if ($order["is_first_comp"] != 0) {
                exit_json(-1, "订单已处理过");
            }
            $this->addCheckLog(session("admin_id"), $order_no, 4, "订单首单审核通过", $commission);
            $this->addOrderCommissionLog(session("admin_id"), 3, $order_no, $order["user_id"], $commission);
            $order->save(["is_first_comp" => 1]);
            exit_json();
        } elseif ($status == 2) {
            if ($order["is_first_comp"] != 0) {
                exit_json(-1, "订单已处理过");
            }
            $this->addCheckLog(session("admin_id"), $order_no, 5, "订单首单审核拒绝", $commission);
            $order->save(["is_first_comp" => 2]);
            exit_json();
        } elseif ($status == 3) {
            if ($order["is_first_comp"] != 1) {
                exit_json(-1, "订单状态错误");
            }
            $this->addCheckLog(session("admin_id"), $order_no, 6, "订单首单审核撤销", $commission);
            $this->addOrderCommissionLog(session("admin_id"), 4, $order_no, $order["user_id"], -$commission);
            $order->save(["is_first_comp" => 2]);
            exit_json();
        } else {
            exit_json(-1, "参数错误");
        }
    }

    public function getOrderCommission($order_no)
    {
        $commission = 0;
        $user_id = model('Order')->where("order_no", $order_no)->value("user_id");
        $vip_code = model("User")->where("id", $user_id)->value("vip_code");
        $agent = model("Admins")->where("vip_code", $vip_code)->find();
        if (in_array(15, explode(',', $agent['role_id']))) {
            if ($agent['agent_cate'] == 1) {
                $c_type = "agent_commission";
            } else {
                $c_type = "parttime_commission";
            }
        } else {
            $c_type = 'salesman_commission';
        }
        $list = model("OrderDet")->where("order_no", $order_no)->select();
        foreach ($list as $value) {
            $commission += $value["price"] * $value["num"] * $value["$c_type"] / 100;
        }
        return round($commission, 2);
    }

    /**
     * 增加订单核算记录
     */
    public function addCheckLog($admin_id, $order_no, $type, $name, $commission)
    {
        model("OrderCommissionOperaLog")->save([
            "order_no" => $order_no,
            "commission_money" => $commission,
            "admin_id" => $admin_id,
            "type" => $type,
            "name" => $name
        ]);
    }

    /**
     * 订单返现日志
     */
    public function addOrderCommissionLog($admin_id, $type, $order_no, $user_id, $commission)
    {
        $vip_code = model("User")->where("id", $user_id)->value("vip_code");
        $agent = model("Admins")->where("vip_code", $vip_code)->find();
        $agent->setInc("total_commission", $commission);
        $agent->setInc("able_commission", $commission);
        model("OrderCommissionLog")->save([
            "order_no" => $order_no,
            "commission_money" => $commission,
            "agent_id" => $agent["id"],
            "user_id" => $user_id,
            "admin_id" => $admin_id,
            "type" => $type
        ]);

    }

    public function downloadExcel($key)
    {
        @ini_set("memory_limit", "128M");
        set_time_limit(0);
        $req_data = json_decode($key, true);
        $param = [];
        foreach ($req_data as $item) {
            $param[$item["name"]] = $item["value"];
        }
//        print_r($param);
        $order = model('order')->alias("a")->join('user b', 'a.user_id=b.id', 'left')->join("Admins c", "b.vip_code=c.vip_code", 'left');
        if ((isset($param["searchKey"]) && $param["searchKey"] != "") && (isset($param["searchValue"]) && $param["searchValue"] != "")) {
            $order->where($param["searchKey"], 'like', "%" . $param["searchValue"] . "%");
        }
        if (isset($param["order_status"]) && $param["order_status"] !== "") {
            $order->where("a.order_status", $param["order_status"]);
        }
        if (isset($param["start_time"]) && $param["start_time"] != "") {
            $order->where("a.create_time", "egt", strtotime($param["start_time"]));
        }
        if (isset($param["end_time"]) && $param["end_time"] != "") {
            $order->where("a.create_time", "elt", strtotime($param["end_time"]) + 86400);
        }
        if (isset($param["agent_name"]) && $param["agent_name"] != "") {
            $order->where("c.name", "like", "%" . $param["agent_name"] . "%");
        }
        $order->where("is_trash", 0);
//        （1）订单编号 、支付订单号、下单时间、下单客户、归属客服、订单状态、支付方式、商品名称、商品价格、商品件数、运费（按照产品单独设置运费）、优惠金额（按照产品单独设置）商品总额（商品价格*商品件数）、商品优惠后金额（商品总额+运费-优惠金额）、支付总金的（整个订单的金额）、发货时间、收货时间

        $order_list = $order->field('a.id, a.order_no, a.order_no_pre, a.create_time, b.user_name, c.name agent_name, a.order_status, a.order_money, a.pay_type, a.send_time, a.sure_time')->order("a.create_time desc")->select();
//        $data = [];
        foreach ($order_list as $item) {
            $product_list = model("OrderDet")->where("order_no", $item['order_no'])->select();
            $item["product_list"] = $product_list;
        }
        $this->assign("order_list", $order_list);
        $this->assign("filename", "订单列表.xls");
        return $this->fetch();
    }


    /**
     * 下载订单
     */
    public function downloadOrder()
    {
        if (request()->isPost()) {
            $start_time = input('start_time');
            $end_time = input('end_time');
            $where['a.create_time'] = [
                ['egt', strtotime($start_time)],
                ['elt', strtotime($end_time . "+1 day")]
            ];
            $where['a.pay_status'] = 1;
            $order_list = model('order')->alias('a')->join('order_refund b', 'a.id=b.order_id', 'left')->field('a.*, b.money refund_money, b.create_time refund_time, b.status refund_status')->where($where)->select();
            $this->assign('list', $order_list);
            $this->assign('filename', date('Y-m-d') . '.xls');
            $android_num = model('user')->where([
                'creattime' => [
                    ['egt', strtotime($start_time)],
                    ['elt', strtotime($end_time . "+1 day")]
                ],
                'device' => 'Android'
            ])->count();
            $iphone_num = model('user')->where([
                'creattime' => [
                    ['egt', strtotime($start_time)],
                    ['elt', strtotime($end_time . "+1 day")]
                ],
                'device' => 'iPhone'
            ])->count();
            $active_num = model('order')->where([
                'create_time' => [
                    ['egt', strtotime($start_time)],
                    ['elt', strtotime($end_time . "+1 day")]
                ]
            ])->group('user_id')->count();
            $reg_data = [
                'android' => $android_num,
                'iphone' => $iphone_num,
                'active_num' => $active_num
            ];
            $this->assign('reg_data', $reg_data);
            $shop_list = model('shop')->column('id', 'shopname');
            $amount = [];
            foreach ($shop_list as $k => $v) {
                $amount["$k"] = model('order')->where([
                    'pay_status' => 1,
                    'is_apply_refund' => ['in', [0, 1, 3]],
                    'shop_id' => $v,
                    'create_time' => [
                        ['egt', strtotime($start_time)],
                        ['elt', strtotime($end_time . "+1 day")]
                    ]
                ])->sum('real_cost');
            }
            $this->assign('amount', $amount);
            return $this->fetch('excel');
        }
        return $this->fetch();
    }

    /**
     * 订单结算详情
     */
    public function checkDetail()
    {
        $order_no = input("order_no");
        $order = model("Order")->where("order_no", $order_no)->find();
        if (!$order) {
            exit("订单不存在");
        } else {
            $det_list = model("OrderDet")->where("order_no", $order_no)->select();
            $user = model("User")->where("id", $order["user_id"])->find();
            $agent = model("Admins")->where("vip_code", $user['vip_code'])->find();
            $this->assign("agent", $agent);
            if (in_array(15, explode(',', $agent['role_id']))) {
                $is_agent = 1;
            } else {
                $is_agent = 0;
            }
            $this->assign("is_agent", 0);
            foreach ($det_list as &$value) {
                if ($is_agent) {
                    $value['commission'] = $value['salesman_commission'];
                } else {
                    if ($agent['agent_cate'] == 1) {
                        $value['commission'] = $value['agent_commission'];
                    } else {
                        $value['commission'] = $value['parttime_commission'];
                    }
                }
            }
            $this->assign("item", $order);
            $this->assign("list", $det_list);
            return $this->fetch('checkDetail');
        }
    }


}