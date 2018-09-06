<?php
/**
 * 支付结果通知
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018.6.4
 * Time: 09:31
 */

namespace app\admin\controller;


use app\common\model\WeiXinPay;
use think\Controller;
use think\Log;

class PayResult extends Controller
{
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * 微信支付回调
     */
    public function weixinPay()
    {
        Log::error(file_get_contents('php://input'));
        $weixinPay = new WeiXinPay();
        $result = $weixinPay->chargeNotify();
        //威富通支付回调
        /*if ($result['code'] == 1) {
            $data = $result['data'];
            $order = model('Order')->where('order_no', $data['out_trade_no'])->find();
            if ($order['order_status'] == 0) {
                if($data['pay_result'] == 0){
//                    用户标识	openid	是	String(128)	用户在商户 appid 下的唯一标识
//                    交易类型	trade_type	是	String(32)	pay.weixin.app
//                    支付结果	pay_result	是	Int	支付结果：0—成功；其它—失败
//                    支付结果信息	pay_info	否	String(64)	支付结果信息，支付成功时为空
//                    平台订单号	transaction_id	是	String(32)	平台交易号
//                    第三方支付单	out_transaction_id	否	String(32)	如：微信支付单号，支付宝支付单号
//                    商户订单号	out_trade_no	是	String(32)	商户系统内部的定单号，32个字符内、可包含字母
//                    总金额	total_fee	是	Int	总金额，以分为单位，不允许包含任何字、符号
//                    现金支付金额	cash_fee	否	Int	现金支付金额订单现金支付金额，详见支付金额
//                    货币种类	fee_type	否	String(8)	货币类型，符合 ISO 4217 标准的三位字母代码，默认人民币：CNY
//                    附加信息	attach	否	String(127)	商家数据包，原样返回预下单时自定义数据
//                    付款银行	bank_type	是	String(16)	银行类型
//                    支付完成时间	time_end	是	String(14)	支付完成时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。时区为GMT+8 beijing。该时间取自平台服务器
                    if($data['total_fee'] == $order['order_money']*100){
                        //订单支付成功
                        $res = $order->save([
                            'pay_type'=>1,
                            'pay_status'=>1,
                            'order_status'=>1,
                            'pay_time'=>$data['time_end'],
                            'transaction_id'=>$data['transaction_id'],
                            'out_transaction_id'=>$data['out_transaction_id']
                        ]);
                        if(!$res){
                            Log::error($order['order_no'].'订单支付成功但处理失败');
                        }
                    }else{
                        Log::error($order['order_no'].'订单支付金额异常');
                    }
                }else{
                    Log::error($order['order_no'].'支付异常，'.$data['pay_info']);
                }
            } else {
                Log::error($order['order_no'] . '订单已支付');
            }
            echo 'success';
        } else {
            Log::error('支付验签失败或参数异常');
            echo 'fail';
        }*/

        if ($result === false) {
            Log::error('支付验签失败或参数异常');

        } else {
            //原生微信支付回调
            if ($result['result_code'] == 'SUCCESS') {
                $order_no = $result['out_trade_no'];
                $transaction_id = $result['transaction_id'];
                $order = model('Order')->where('order_no', $order_no)->find();
                if ($order) {
                    if ((float)$order['order_money'] * 100 == $result['total_fee']) {
                        if ($result['trade_type'] == 'APP') {
                            $pay_type = 1;
                        } else {
                            $pay_type = 3;
                        }
                        $order->save(['out_transaction_id' => $transaction_id, 'pay_type' => $pay_type, 'pay_status' => 1, 'pay_time' => $result['time_end'], 'order_status'=>1]);
                        try{
                            $this->orderSolve($order_no);
                        } catch (\Exception $e){
                            Log::error('库存处理异常'.$order_no);
                        }
                    } else {
                        Log::error('支付金额错误' . $order_no);
                    }
                } else {
                    Log::error('订单不存在' . $order_no);
                }
            } else {
                Log::error('支付失败，订单号：' . $result['out_trade_no']);
            }
        }
        echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';

    }

    /**
     * 订单支付库存处理
     * @param $order_no
     */
    public function orderSolve($order_no)
    {
        $order_det = model('OrderDet')->where('order_no', $order_no)->select();
        foreach ($order_det as $item) {
            model('ProductAttr')->where('product_id', $item['product_id'])->where('prop_value_attr', $item['prop_value_attr'])->setDec('remain', $item['num']);
            model('Product')->where('id', $item['product_id'])->setInc('sell_num', $item['num']);
        }
    }

    /**
     * 格式化支付返回信息
     * @param $orderInfo
     * @param $pay_type
     * @return array
     */
    private function formatRes($orderInfo, $pay_type)
    {
        $payInfo = [];
        if ($pay_type == 1) {
            $payInfo['order_no'] = $orderInfo['out_trade_no'];
            $payInfo['out_transaction_id'] = $orderInfo['trade_no'];
            $payInfo['order_money'] = $orderInfo['total_amount'];
        } else if ($pay_type == 2) {
            $payInfo['order_no'] = $orderInfo['out_trade_no'];
            $payInfo['out_transaction_id'] = $orderInfo['transaction_id'];
            $payInfo['order_money'] = $orderInfo['total_fee'] / 100;
        }
        return $payInfo;
    }
}