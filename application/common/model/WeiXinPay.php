<?php

namespace app\common\model;

use think\Log;

require_once VENDOR_PATH . 'WFT/request.php';


class WeiXinPay
{
    /**
     * 统一下单
     * @param $orderInfo
     * @param $notify_url
     * @return mixed
     */
    public function createPrePayOrder($orderInfo)
    {

        $request = new \request();
        $payInfo = $request->submitOrderInfo($orderInfo);
        return $payInfo;

    }

    /**
     * 支付回调校验
     * @return array|bool|string
     */
    public function chargeNotify()
    {
        $request = new \request();
        $result = $request->callback();
        return $result;
    }

    /**
     * 退款
     */
    public function refund($order)
    {

    }

}