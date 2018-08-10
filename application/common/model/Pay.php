<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018.6.1
 * Time: 11:24
 */

namespace app\common\model;



use think\Log;

class Pay
{
    private $alipay;
    private $weixinpay;

    public function __construct()
    {
        $this->alipay = new AliPay();
        $this->weixinpay = new WeiXinPay();
    }
    /**
     * 订单支付信息
     * @param $order_no
     * @param $pay_type
     * @param $pay_event
     * @return array|bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function payOrder($order_no, $pay_type, $pay_event)
    {
        $payData = [];
        if($pay_event == 'order'){
            $orderInfo = \model('order')->where('order_no', $order_no)->find();
            $payData = [
                'subject' => '订单支付-' . $orderInfo['shop_name'],
                'body' => '',
                'out_trade_no' => $orderInfo['order_no'],
                'total_amount' => $orderInfo['real_cost'],
                'trade_type' => 'APP',
                'pay_event' => $pay_event
            ];
        }else if($pay_event == 'account'){
            $orderInfo = \model('charge_order')->where('order_no', $order_no)->find();
            $payData = [
                'subject' => '会员充值',
                'body' => '充值金额'.$orderInfo['money'].',赠送金额'.$orderInfo['given_money'],
                'out_trade_no' => $orderInfo['order_no'],
                'total_amount' => $orderInfo['money'],
                'trade_type' => 'APP',
                'pay_event' => $pay_event
            ];
        }
        $orderString = $this->createOrder($pay_type, $payData, config('notify_url'));
        return $orderString;
    }

    /**
     * 创建订单,获取支付订单信息
     * @param $pay_type
     * @param array $orderInfo
     * @param string $notify_url
     * @return array|bool|string
     * $orderInfo = ['out_trade_no'=>'', 'subject'=>'', 'body'=>'', 'total_amount'=>10.22, 'trade_type'=>'APP'] 订单格式
     */
    public function createOrder($pay_type, $orderInfo = [], $notify_url='')
    {
        if(!isset($orderInfo)){
            return false;
        }
        if(!$notify_url){
            return false;
        }
        $payOrder = new PayOrder();
        $payOrder->allowField(true)->save([
            'pay_event'=>$orderInfo['pay_event'],
            'pay_type'=>$pay_type,
            'order_no'=>$orderInfo['out_trade_no'],
            'total_money'=>$orderInfo['total_amount'],
            'user_id'=>USER_ID
        ]);
        //重置支付订单号
        $payId = $payOrder->getLastInsID();
        $orderInfo['out_trade_no'] = $payId."_yb1";
        if ($pay_type == 1) {
            //支付宝
            $orderString = $this->alipay->createAppPayOrder($orderInfo, $notify_url);
            return $orderString;

        } elseif ($pay_type == 2) {
            //微信
            $orderString = $this->weixinpay->createPrePayOrder($orderInfo);
            return $orderString;
        } else {
            return false;
        }
    }

    /**
     * 支付回调验证
     * 支付方式
     * @param $pay_type
     * @return array|bool|string
     */
    public function validate($pay_type)
    {
        if($pay_type == 1){
            $result = $this->alipay->rechargeValidate();
        }elseif ($pay_type == 2){
            $result = $this->weixinpay->chargeNotify();
        }else{
            $result = false;
        }
        return $result;
    }

    /**
     * 订单退款
     * @param $refund_id
     * @param $money
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     *
     */
    public function refundOrder($refund_id, $money)
    {
        $order_refund = model('order_refund')->where('id', $refund_id)->find();
        $order = model('order')->where('id', $order_refund['order_id'])->find();
        $refund_config = [
            'trade_no'=>$order['trade_no'],
            'refund_money'=>$order_refund['money'],
            'refund_reason'=>$order_refund['remarks'],
            'refund_id'=>$order_refund['id'],
            'total_money'=>$order['real_cost'],
            'shop_id'=>$order['shop_id']
        ];
        if($order['pay_type'] == 1){
            //支付宝支付退款
//            try{
                $res = $this->alipay->refund($refund_config);
//            }catch (\Exception $e){
//                $res = false;
//            }
        }elseif($order['pay_type'] == 2){
            //微信支付退款
            $res = $this->weixinpay->refund($refund_config);
        }else{
            return false;
        }
        if($res){
            return true;
        }else{
            return false;
        }
    }


}
