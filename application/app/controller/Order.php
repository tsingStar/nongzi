<?php
/**
 * 订单控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018.5.29
 * Time: 14:59
 */

namespace app\app\controller;


use app\common\model\WeiXinPay;
use app\common\model\AliPay;
use think\Log;

class Order extends BaseUser
{
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * 生成预支付订单 购物车来源
     */
    public function makePreOrderByCart()
    {
        $cart_ids = input('cart_ids');
        $carts = model('ShopCart')->whereIn('id', $cart_ids)->select();
        $send_fee = 0;
        $count = 0;
        $total_money = 0;
        $pros = [];
        foreach ($carts as $c) {
            $p = model('Product')->where('id', $c['product_id'])->find();
            $prop_attr = model('ProductAttr')->where('prop_value_attr', $c['prop_value_attr'])->where('product_id', $c['product_id'])->find();
            $total_price = sprintf("%.2f", $prop_attr['price_comb'] * $c['num']);
            $temp = [
                'name' => $p['name'],
                'thumb_img' => __URL__ . $p['thumb_img'],
                'prop_value_attr' => $prop_attr['prop_value_attr'],
                'price_comb' => $prop_attr['price_comb'],
                'prop_name' => $prop_attr['prop_value_name'],
                'num' => $c['num'],
                'total_price' => $total_price,
                'product_id' => $c['product_id'],
                'agent_commission'=>$p["agent_commission"],
                'salesman_commission'=>$p["salesman_commission"],
                "parttime_commission"=>$p["parttime_commission"],
                "send_fee"=>$p["send_fee"]
            ];
            if ($p['is_send'] != 1) {
                $send_fee += $c['num'] * $p['send_fee'];
            }
            $count += $c['num'];
            $total_money += $total_price;
            $pros[] = $temp;
        }
        $data = [
            'products' => $pros,
            'send_fee' => $send_fee,
            'total_money' => $total_money,
            'cart_ids' => $cart_ids,
            'src' => 1,
            'count' => $count,
            'order_money' => $send_fee + $total_money
        ];
        $prepay_id = uniqid(USER_ID);
        $data['prepay_id'] = $prepay_id;
        cache(USER_ID . 'order', $data);
        exit_json(1, '请求成功', $data);

    }

    /**
     *     生成预支付订单 商品立即购买
     */
    public function makePreOrderByPro()
    {
        $product_id = input('product_id');
        $prop_value = input('prop_value_attr');
        $num = input('num');
        $p = model('Product')->where('id', $product_id)->find();
        $prop_attr = model('ProductAttr')->where('product_id', $product_id)->where('prop_value_attr', $prop_value)->find();
        if ($prop_attr['remain'] < $num) {
            exit_json(-1, '商品库存不足');
        }
        $total_num = model('OrderDet')->where('user_id', USER_ID)->where('product_id', $product_id)->sum('num');
        if($p['limit_num']>0 && $p['limit_num']<$total_num+$num){
            exit_json(-1,'商品数量大于限购数量，当前商品限购'.$p['limit_num']);
        }
        $total_money = sprintf('%.2f', $num * $prop_attr['price_comb']);
        $pros = [
            [
                'name' => $p['name'],
                'thumb_img' => __URL__ . $p['thumb_img'],
                'prop_value_attr' => $prop_value,
                'price_comb' => $prop_attr['price_comb'],
                'prop_name' => $prop_attr['prop_value_name'],
                'num' => $num,
                'total_price' => $total_money,
                'product_id' => $product_id,
                'agent_commission'=>$p["agent_commission"],
                'salesman_commission'=>$p["salesman_commission"],
                "parttime_commission"=>$p["parttime_commission"],
                "send_fee"=>$p["send_fee"]
            ]
        ];
        $send_fee = 0;
        if ($p['is_send'] != 1) {
            $send_fee = $num * $p['send_fee'];
        }
        $data = [
            'products' => $pros,
            'send_fee' => $send_fee,
            'total_money' => $total_money,
            'cart_ids' => '',
            'src' => 2,
            'count' => $num,
            'order_money' => $send_fee + $total_money
        ];
        $prepay_id = uniqid(USER_ID);
        $data['prepay_id'] = $prepay_id;
        cache(USER_ID . 'order', $data);
        exit_json(1, '请求成功', $data);
    }

    /**
     * 生成订单
     */
    public function makeOrder()
    {

        $prepay_id = input('prepay_id');
        $data = cache(USER_ID . 'order');
        if ($data['prepay_id'] == $prepay_id) {
            $address = input('address');
            $receiver_telephone = input('receiver_telephone');
            $receiver_name = input('receiver_name');
            $remarks = input('remarks');
            if (!$address || !$receiver_telephone || !$receiver_name) {
                exit_json(-1, '收货人信息不能为空');
            }
            $order_no = getOrderNo();
            model('Order')->startTrans();
            model('OrderDet')->startTrans();
            //订单基本信息
            $order_data = [
                'order_no' => $order_no,
                'user_id' => USER_ID,
                'receiver_name' => $receiver_name,
                'address' => $address,
                'receiver_telephone' => $receiver_telephone,
                'total_money' => $data['total_money'],
                'send_fee' => $data['send_fee'],
                'remarks' => $remarks,
                'order_money' => $data['total_money'] + $data['send_fee']
            ];
            //订单详情信息
            $flag = true;
            $tips = "";
            $order_detail = [];
            foreach ($data['products'] as $item) {
                $temp = [
                    'order_no' => $order_no,
                    'user_id' => USER_ID,
                    'product_id' => $item['product_id'],
                    'prop_value_attr' => $item['prop_value_attr'],
                    'price' => $item['price_comb'],
                    'num' => $item['num'],
                    'name' => $item['name'],
                    'prop_name' => $item['prop_name'],
                    'thumb_img' => $item['thumb_img'],
                    'agent_commission'=>$item["agent_commission"],
                    'salesman_commission'=>$item["salesman_commission"],
                    "parttime_commission"=>$item["parttime_commission"],
                    "pre_send_fee"=>$item["send_fee"]*$item["num"],
                    "send_fee"=>$item["send_fee"]*$item["num"]
                ];
                $prop_attr = model('ProductAttr')->where('product_id', $item['product_id'])->where('prop_value_attr', $item['prop_value_attr'])->find();
                if ($prop_attr['remain']<$item['num']){
                    $flag=false;
                    $tips .= $item['name'].';';
                }
                $order_detail[] = $temp;
            }
            if (!$flag){
                exit_json(-1, $tips.'商品库存不足');

            }
            try {
                $res = model('Order')->save($order_data);
                $res1 = model('OrderDet')->saveAll($order_detail);
                if ($res && $res1) {
                    if ($data['src'] == 1) {
                        model('ShopCart')->whereIn('id', $data['cart_ids'])->delete();
                    }
                    model('Order')->commit();
                    model('OrderDet')->commit();
                    cache(USER_ID . 'order', null);
                    exit_json(1, '订单生成成功', [
                        'order_no' => $order_no,
                        "order_money"=>$order_data['order_money']
                    ]);
                } else {
                    model('Order')->rollback();
                    model('OrderDet')->rollback();
                    exit_json(-1, '订单生成失败');
                }
            } catch (\Exception $e) {
                model('Order')->rollback();
                model('OrderDet')->rollback();
                exit_json(-1, '订单生成失败123');
            }
        } else {
            exit_json(-1, '参数错误');
        }
    }


    /**
     * 订单支付
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function payOrder()
    {
        $order_no = input('order_no');
        $pay_type = input('pay_type');
        $orderInfo = model('Order')->where('order_no', $order_no)->find();
        if ($orderInfo['user_id'] != USER_ID) {
            exit_json(-1, '不是当前登陆用户订单');
        }
        if ($orderInfo['order_status'] != 0) {
            exit_json(-1, '订单状态不支持支付');
        }

//        if ($orderInfo->getData('create_time') < time() - 30 * 60) {
//            $orderInfo->save(['order_status' => 5]);
//            exit_json(-1, '订单超时');
//        }
        
        //pay_type 支付方式   1 威富通 微信支付  2 支付宝 3  小程序支付
        $pay_info = [
            'wxpay' => "",
            'alipay' => "",
            'xcxpay' => ""
        ];
        $order_no_pre = getOrderNo().rand(100,999);
        model("order_pre")->save([
            "order_no"=>$order_no,
            "order_no_pre"=>$order_no_pre,
            "create_time"=>time()
        ]);
        $order = model('Order')->where('order_no', $order_no)->find();
        if ($pay_type == 1) {
            $pay_data = [
                'out_trade_no' => $order_no_pre,
                'body' => "订单支付",
                'total_fee' => $order['order_money'] * 100,
                'mch_create_ip' => getIp(),
                'subject'=>'订单支付',
                'total_amount'=>$order['order_money'],
                'trade_type'=>'APP'
            ];
            $weixin = new WeiXinPay();
            $result = $weixin->createPrePayOrder($pay_data, config('notify.weixin'));
            if($result === false){
                exit_json(-1, '订单已处理，请联系管理员');
            }
            if (isset($result['status'])) {
                exit_json(-1, '系统错误，稍后重试');
            } else {
                $pay_info['wxpay'] = $result;
            }
        } else if ($pay_type == 2) {
            $ali_pay = new AliPay();
            $orderInfo = [
                'subject' => '订单支付-镰刀农业',
                'body' => '',
                'out_trade_no' => $order_no_pre,
                'total_amount' => $order['order_money'],
                'trade_type' => 'APP',
            ];
            $result = $ali_pay->createAppPayOrder($orderInfo, config('notify.ali'));
            $pay_info['alipay'] = $result;

        } else if ($pay_type == 3) {
            $pay_data = [
                'out_trade_no' => $order_no_pre,
                'body' => "订单支付",
                'total_fee' => $order['order_money'] * 100,
                'mch_create_ip' => getIp(),
                'subject'=>'订单支付',
                'total_amount'=>$order['order_money'],
                'trade_type'=>'JSAPI'
            ];
            $pay_data['open_id'] = model('User')->where('id', USER_ID)->value('open_id');
            $weixin = new WeiXinPay();
            $result = $weixin->createPrePayOrder($pay_data, config('notify.weixin'));
            if (isset($result['status'])) {
                exit_json(-1, '系统错误，稍后重试');
            } else {
                $pay_info['xcxpay'] = $result;
            }
        } else {
            exit_json(-1, '参数错误');
        }
        model("CollectPay")->save([
            "order_no"=>$order_no,
            "user_id"=>USER_ID
        ]);
        exit_json(1, '请求成功', $pay_info);
    }

    /**
     * 获取订单列表
     */
    public function getOrderList()
    {
        $order_status = input('order_status');
        $page = input('page') ? input('page') : 0;
        $pageNum = input('pageNum') ? input('pageNum') : 10;
        $offset = $page * $pageNum;
//        $orderList = model('Order')->where('user_id', USER_ID)->where('order_status', $order_status)->field('order_no, receiver_name, receiver_telephone, address, remarks, send_fee, order_status, order_money, create_time')->limit($offset, $pageNum)->order('create_time desc')->select();
        $orderList = model('Order')->alias('a')->join('OrderRefund b', 'a.order_no=b.order_no', 'left')->where('a.user_id', USER_ID)->where('a.is_trash', 0)->where('a.order_status', $order_status)->field('a.order_no, a.receiver_name, a.receiver_telephone, a.address, a.remarks, a.send_fee, a.order_status, a.order_money, a.create_time, b.status refund_status')->limit($offset, $pageNum)->order('a.create_time desc')->select();
        foreach ($orderList as $order) {
            $order_no = $order['order_no'];
            $order_det = model('OrderDet')->where('order_no', $order_no)->field('id det_id, name, thumb_img, prop_value_attr, prop_name, price price_comb, num, price*num total_price, product_id')->select();
            $order['order_det'] = $order_det;
            $order['refund_status'] = is_null($order['refund_status'])?-1:$order['refund_status'];
        }
        exit_json(1, '请求成功', $orderList);
    }

    /**
     * 获取订单详情
     */
    public function getOrderDetail()
    {
        $order_no = input('order_no');
        $order = model('Order')->where('order_no', $order_no)->field('order_no, receiver_name, receiver_telephone, address, remarks, send_fee, order_status, order_money, create_time, forecast_receive')->find();
        if(!$order){
            exit_json(-1, '订单不存在');
        }
        $co = model("CollectOrder")->where("order_no", $order_no)->where("day", date("Y-m-d"))->find();
        if($co){
            $co->setInc("nums", 1);
        }else{
            model("CollectOrder")->save(["order_no"=>$order_no, "day"=>date("Y-m-d"), "nums"=>1, "user_id"=>USER_ID]);
        }
        $order['order_det'] = model('OrderDet')->where('order_no', $order_no)->field('id det_id, name, thumb_img, prop_value_attr, prop_name, price price_comb, num, price*num total_price, product_id, discount, send_fee')->select();
        exit_json(1, '请求成功', $order);

    }

    /**
     * 获取求购/供应
     */
    public function getBuyAndSupply()
    {
        $type = input('type');
        if ($type != 1 && $type != 2) {
            exit_json(-1, '参数错误');
        }
        $list = model('BuySupply')->where('type', $type)->where('user_id', USER_ID)->field('product_name, num, telephone, description, remarks')->select();
        exit_json(1, '请求成功', $list);
    }

    /**
     * 确认收获
     */
    public function sureOrder()
    {
        $order_no = input('order_no');
        $orderModel = model('order');
        $order = $orderModel->where('order_no', $order_no)->find();
        if ($order['order_status'] == 2) {
            if ($order['user_id'] != USER_ID) {
                exit_json(-1, '不是当前登陆用户订单');
            }
            $order->save(['order_status' => 3, 'sure_time' => date('Y-m-d H:i')]);
            exit_json();
        } else {
            exit_json(-1, '订单状态错误');
        }
    }

    /**
     * 申请退款
     */
    public function refundOrder()
    {
        Log::error($_POST);
        $order_no = input('order_no');
        //退款类型 1 整单  2 单个商品
        $refund_type = input('refund_type');
        $refund_detail = input('refund_detail');

        if ($refund_type == 2) {
            if (count(explode(',', $refund_detail)) < 1) {
                exit_json(-1, '退货商品不能为空');
            }
        }
        model('Order')->startTrans();
        model('OrderRefund')->startTrans();
        $order = model('Order')->where('order_no', $order_no)->find();
        if($order['user_id'] != USER_ID){
            exit_json(-1,'订单与当前登陆用户不匹配');
        }
        if ($order) {
            if ($order['order_status'] == 0 || $order['order_status'] == 5) {
                exit_json(-1, '订单未支付或订单已关闭');
            }
            if ($order['is_apply_refund'] == 1) {
                exit_json(-1, '申请已提交，等待商家处理');
            }
            if ($order['is_apply_refund'] != 0) {
                exit_json(-1, '退款申请已处理，请联系客服');
            }
            $res = $order->save(['order_status' => 4, 'is_apply_refund' => 1]);
            //if ($refund_type == 2) {
            //    $c = model('OrderDet')->where('order_no', $order_no)->count();
            //    if ($c == count(explode(',', $refund_detail))) {
            //        $refund_type = 1;
            //    }
            //}
            if($refund_type == 1){
                $od = model('OrderDet')->where('order_no', $order_no)->select();
                $det = [];
                foreach ($od as $o){
                    $det[] = $o['id'];
                }
                $refund_detail = join(',', $det);
            }
            $res1 = model('OrderRefund')->save([
                'order_no' => $order['order_no'],
                'refund_type' => $refund_type,
                'refund_detail' => $refund_detail,
                'remarks'=>input('remarks')
            ]);
            if ($res && $res1) {
                model('Order')->commit();
                model('OrderRefund')->commit();
                exit_json(1, '申请已提交，等待商家审核');
            } else {
                model('Order')->rollback();
                model('OrderRefund')->commit();
                exit_json(-1, '系统错误，请联系客服');
            }
        } else {
            exit_json(-1, '订单不存在');
        }
    }

    /**
     * 获取退款详情
     */
    public function getRefundInfo()
    {
        $order_no = input('order_no');
        $refund = model('OrderRefund')->where('order_no', $order_no)->field('id refund_id, refund_address, refund_name, refund_telephone, refund_type, order_no, status, refund_detail')->find();
        $time = model('Order')->where('order_no', $order_no)->value('create_time');
        if ($refund) {
            $refund_det = model('OrderDet')->whereIn('id', $refund['refund_detail'])->field('id det_id, name, thumb_img, prop_value_attr, prop_name, price price_comb, num, price*num total_price, product_id')->select();
            $refund['refund_detail'] = $refund_det;
            $refund['create_time'] = $time;
            exit_json(1, '请求成功', $refund);
        } else {
            exit_json(-1, '订单不存在');
        }
    }

    /**
     * 提交退货信息
     */
    public function submitRefundInfo()
    {
        $refund_id = input('refund_id');
        $send_no = input('send_no');
        $receive_info = input('receive_info');
        $of = model('OrderRefund')->where('id', $refund_id)->find();
        if ($of) {
            if ($of['refund_type'] == 2 && $receive_info == "") {
                exit_json(-1, '部分商品退货需提交收款信息');
            }
            if($of['send_no'] != ""){
                exit_json(-1,'退货信息已提交,请勿重复提交');
            }
            $res = $of->save([
                'send_no' => $send_no,
                'receive_info' => $receive_info,
            ]);
            if ($res) {
                exit_json();
            } else {
                exit_json(-1, '信息提交失败');
            }
        } else {
            exit_json(-1, '退款信息不存在');
        }
    }

    //TODO 待处理


    /**
     * 取消订单
     */
    public function cancelOrder()
    {
        $order_no = input('order_no');
        $order = model('order')->where(['order_no' => $order_no])->find();
        if ($order['user_id'] != USER_ID) {
            exit_json(-1, '不是当前登陆用户订单');
        }
        if ($order['order_status'] != 0) {
            exit_json(-1, '已支付订单不可以取消');
        } else {
            model('order')->where('order_no', $order_no)->delete();
            model('orderDet')->where('order_no', $order_no)->delete();
            exit_json();
        }
    }


    /**
     * 获取申请退款状态
     */
    public function getRefundStatus()
    {
        $order_no = input('order_no');
        $order = model('order')->where('order_no', $order_no)->find();
        if ($order) {
            if ($order['is_refund'] == 1 && $order['order_status'] == 1) {
                exit_json(1, '请求成功', ['refund_status' => 1]);
            } else {
                exit_json(1, '请求成功', ['refund_status' => -1]);
            }
        } else {
            exit_json(-1, '订单不存在');
        }
    }


    /**
     * 删除订单
     */
    public function delOrder()
    {
        $order_no = input('order_no');
        $order = model('order')->where('order_no', $order_no)->find();
        if ($order['user_id'] > 3) {
            $order->save(['is_del' => 1]);
            exit_json();
        } else {
            exit_json(-1, '当前订单状态不允许删除');
        }
    }


}