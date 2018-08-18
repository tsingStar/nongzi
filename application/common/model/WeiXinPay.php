<?php

namespace app\common\model;

use think\Log;

//require_once VENDOR_PATH . 'WFT/request.php';

require_once VENDOR_PATH . 'WeiXin/WxPay.Api.php';


class WeiXinPay
{
//    /**
//     * 统一下单
//     * @param $orderInfo
//     * @param $notify_url
//     * @return mixed
//     */
//    public function createPrePayOrder($orderInfo, $notify_url)
//    {
//
//        $request = new \request();
//        $payInfo = $request->submitOrderInfo($orderInfo, $notify_url);
//        return $payInfo;
//
//    }
//
//    /**
//     * 支付回调校验
//     * @return array|bool|string
//     */
//    public function chargeNotify()
//    {
//        $request = new \request();
//        $result = $request->callback();
//        return $result;
//    }
//
//    /**
//     * 退款
//     */
//    public function refund($order)
//    {
//
//    }

    /**
     * 统一下单
     * @param $orderInfo
     * @param $notify_url
     * @return mixed
     */
    public function createPrePayOrder($orderInfo, $notify_url)
    {

        $inputObj = new \WxPayUnifiedOrder();
        $inputObj->SetBody($orderInfo['subject']);
        $inputObj->SetDetail($orderInfo['body']);
        $inputObj->SetOut_trade_no($orderInfo['out_trade_no']);
        $inputObj->SetTotal_fee($orderInfo['total_amount'] * 100);
        $inputObj->SetNotify_url($notify_url);
        $inputObj->SetTrade_type($orderInfo['trade_type']);
        if($orderInfo['trade_type'] == 'APP'){
            $inputObj->SetAppid(config('weixin.app_id'));
            $inputObj->SetMch_id(config('weixin.mch_id'));
            $is_app = 1;
        }else{
            $inputObj->SetAppid(config('xiaochengxu.app_id'));
            $inputObj->SetMch_id(config('xiaochengxu.mch_id'));
            $is_app = 0;
        }
        if(isset($orderInfo['open_id']) && $orderInfo['open_id'] != ""){
            $inputObj->SetOpenid($orderInfo['open_id']);
        }
        try {
            $orderString = \WxPayApi::unifiedOrder($inputObj, 6, $is_app);
            return $orderString;
        } catch (\Exception $e) {
            Log::error('微信支付异常：' . $e->getMessage());
            return false;
        }
    }

    /**
     * 支付回调校验
     * @return array|bool|string
     */
    public function chargeNotify()
    {
        $result = \WxPayApi::notify();
//        if($result === false){
//            return '签名错误';
//        }else{
        return $result;
//        }

    }

    /**
     * 退款
     */
    public function refund($order)
    {
        $inputObj = new \WxPayRefund();
        $inputObj->SetTransaction_id($order['trade_no']);
        $inputObj->SetOut_refund_no($order['refund_id']);
        $inputObj->SetTotal_fee($order['total_money'] * 100);
        $inputObj->SetRefund_fee($order['refund_money'] * 100);
        $inputObj->SetOp_user_id($order['shop_id']);
        if($order['pay_status'] == 3){
            $inputObj->SetMch_id(config('xiaochengxu.mch_id'));
            $inputObj->SetAppid(config('xiaochengxu.app_id'));
            $is_app = 0;
        }else{
            $inputObj->SetMch_id(config('weixin.mch_id'));
            $inputObj->SetAppid(config('weixin.app_id'));
            $is_app = 1;
        }
        $result = \WxPayApi::refund($inputObj, 6, $is_app);
        Log::error('微信退款记录' . json_encode($result));
        if ($result['return_code'] == "SUCCESS" && $result['result_code'] == "SUCCESS") {
            return true;
        } else {
            Log::error("微信退款失败：".$result["err_code_des"]);
            return false;
        }
    }


}