<?php

namespace app\common\model;

use think\Log;

class AliPay
{

    protected $aop;
    protected $request;
    protected $pagePayRequest;

    public function __construct()
    {
        vendor('Ali.AopClient');
        $this->aop = new \AopClient();
        $this->aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $this->aop->appId = config('ali.app_id');
//		$this->aop->rsaPrivateKey = '';
        $this->aop->rsaPrivateKeyFilePath = config('ali.private_key_path');
        $this->aop->format = "json";
        $this->aop->charset = "UTF-8";
        $this->aop->signType = "RSA2";
        //$this->aop->alipayrsaPublicKey = '';
        $this->aop->alipayPublicKey = config('ali.public_key_path');
        vendor('Ali.request.AlipayTradeAppPayRequest');
        vendor('Ali.request.AlipayTradePagePayRequest');
        $this->request = new \AlipayTradeAppPayRequest();
        $this->pagePayRequest = new \AlipayTradePagePayRequest();
    }

    /**
     * 支付宝支付
     * @param array $orderInfo
     * @param string $notify_url
     * @return string
     */
    function createAppPayOrder($orderInfo, $notify_url)
    {
        $payInfo['body'] = $orderInfo['body'];
        $payInfo['subject'] = $orderInfo['subject'];
        $payInfo['out_trade_no'] = $orderInfo['out_trade_no'];
        $payInfo['total_amount'] = sprintf("%.2f", $orderInfo['total_amount']);
        $payInfo['timeout_express'] = "30m";
        $payInfo['product_code'] = "QUICK_MSECURITY_PAY";
        $bizcontent = json_encode($payInfo);
        $this->request->setNotifyUrl($notify_url);
        $this->request->setBizContent($bizcontent);
        //这里和普通的接口调用不同，使用的是sdkExecute
        $response = $this->aop->sdkExecute($this->request);
        return $response;//就是orderString 可以直接给客户端请求，无需再做处理。
    }


    function rechargeValidate()
    {
        $flag = $this->aop->rsaCheckV1($_POST, NULL, "RSA2");
        if ($flag) {
            return $_POST;
        } else {
            Log::error("支付参数异常" . json_encode($_POST));
            return false;
        }
    }


    /**
     * alipay.trade.page.pay
     * @param $builder 业务参数，订单信息。
     * @param $return_url 同步跳转地址，公网可以访问
     * @param $notify_url 异步通知地址，公网可以访问
     * @return $response 支付宝返回的信息
     */
    function createPagePayOrder($orderInfo, $return_url, $notify_url)
    {

        $orderInfo['timeout_express'] = "30m";
        $orderInfo['product_code'] = "FAST_INSTANT_TRADE_PAY";
        $biz_content = json_encode($orderInfo);
        $this->pagePayRequest->setNotifyUrl($notify_url);
        $this->pagePayRequest->setReturnUrl($return_url);
        $this->pagePayRequest->setBizContent($biz_content);
        // 首先调用支付api
        $response = $this->aopclientRequestExecute($this->pagePayRequest, true);
        // $response = $response->alipay_trade_wap_pay_response;
        return $response;
    }

    /**
     * sdkClient
     * @param $request 接口请求参数对象。
     * @param $ispage  是否是页面接口，电脑网站支付是页面表单接口。
     * @return $response 支付宝返回的信息
     */
    function aopclientRequestExecute($request, $ispage = false)
    {
        if ($ispage) {
            $result = $this->aop->pageExecute($request, "post");
            echo $result;
        } else {
            $result = $this->aop->Execute($request);
        }

        //打开后，将报文写入log文件
        //$this->writeLog("response: ".var_export($result,true));
        return $result;
    }

    /**
     * @param array $order 退款基本信息
     * @throws \Exception
     * @return array
     */
    function refund($order)
    {
        vendor('Ali.request.AlipayTradeRefundRequest');
        $request = new \AlipayTradeRefundRequest ();
        $biz_content =  [
            'trade_no'=>$order['trade_no'],
            'refund_amount'=>$order['refund_money'],
            'refund_reason'=>$order['refund_reason'],
            'out_request_no'=>$order['refund_id'],
        ];
        $bizContent = json_encode($biz_content);
        $request->setBizContent($bizContent);
        $result = $this->aop->execute($request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        Log::error($responseNode);
        if (!empty($resultCode) && $resultCode == 10000) {
//            echo "成功";
//            return true;
            return [
                "code"=>1,
                "msg"=>'退款成功'
            ];
        } else {
//            echo "失败";
//            return false;
            return [
                "code"=>0,
                "msg"=>'退款失败'
            ];
        }
    }

}