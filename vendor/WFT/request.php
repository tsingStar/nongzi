<?php
/**
 * 支付接口调测例子
 * ================================================================
 * index 进入口，方法中转
 * submitOrderInfo 提交订单信息
 * queryOrder 查询订单
 *
 * ================================================================
 */
require('Utils.class.php');
require('config/config.php');
require('class/RequestHandler.class.php');
require('class/ClientResponseHandler.class.php');
require('class/PayHttpClient.class.php');

Class Request
{
    //$url = 'http://192.168.1.185:9000/pay/gateway';

    private $resHandler = null;
    private $reqHandler = null;
    private $pay = null;
    private $cfg = null;

    public function __construct()
    {
        $this->Request();
    }

    public function Request()
    {
        $this->resHandler = new ClientResponseHandler();
        $this->reqHandler = new RequestHandler();
        $this->pay = new PayHttpClient();
        $this->cfg = new Config();

        $this->reqHandler->setGateUrl($this->cfg->C('url'));

        $sign_type = $this->cfg->C('sign_type');

        if ($sign_type == 'MD5') {
            $this->reqHandler->setKey($this->cfg->C('key'));
            $this->resHandler->setKey($this->cfg->C('key'));
            $this->reqHandler->setSignType($sign_type);
        } else if ($sign_type == 'RSA_1_1' || $sign_type == 'RSA_1_256') {
            $this->reqHandler->setRSAKey($this->cfg->C('private_rsa_key'));
            $this->resHandler->setRSAKey($this->cfg->C('public_rsa_key'));
            $this->reqHandler->setSignType($sign_type);
        }
    }

    public function index()
    {
        $method = isset($_REQUEST['method']) ? $_REQUEST['method'] : 'submitOrderInfo';
        switch ($method) {
            case 'submitOrderInfo'://提交订单
                $this->submitOrderInfo();
                break;
            case 'queryOrder'://查询订单
                $this->queryOrder();
                break;
            case 'submitRefund'://提交退款
                $this->submitRefund();
                break;
            case 'queryRefund'://查询退款
                $this->queryRefund();
                break;
            case 'callback':
                $this->callback();
                break;
        }
    }

    /**
     * 提交订单信息
     */
    public function submitOrderInfo($order, $notify_url)
    {
        $this->reqHandler->setReqParams($order, array('method'));
        $this->reqHandler->setParameter('service', 'unified.trade.pay');//非原生统一下单
//        $this->reqHandler->setParameter('service', 'pay.weixin.raw.app');//原生统一下单
        $this->reqHandler->setParameter('mch_id', $this->cfg->C('mchId'));//必填项，商户号，由平台分配
//        $this->reqHandler->setParameter('appid', $this->cfg->C('appid'));//如果调用原生统一下单，则此参数必填
//        $this->reqHandler->setParameter('sub_appid', $this->cfg->C('appid'));//如果调用原生统一下单，则此参数必填
        $this->reqHandler->setParameter('version', '2.0');
        $this->reqHandler->setParameter('sign_type', $this->cfg->C('sign_type'));
        //$this->reqHandler->setParameter('op_shop_id','1314');
        //$this->reqHandler->setParameter('device_info','长江');
        //$this->reqHandler->setParameter('op_device_id','东风一号');
        // $this->reqHandler->setParameter('limit_credit_pay','1');   //是否支持信用卡，1为不支持，0为支持
        //$this->reqHandler->setParameter('groupno','8111100093');
        //通知地址，必填项，接收平台通知的URL，需给绝对路径，255字符内格式如:http://wap.tenpay.com/tenpay.asp
        //$notify_url = 'http://'.$_SERVER['HTTP_HOST'];
        //$this->reqHandler->setParameter('notify_url',$notify_url.'/payInterface/request.php?method=callback');
        $this->reqHandler->setParameter('notify_url', $notify_url);//商户需传自己的
        $this->reqHandler->setParameter('nonce_str', mt_rand(time(), time() + rand()));//随机字符串，必填项，不长于 32 位
        $this->reqHandler->createSign();//创建签名

        $data = Utils::toXml($this->reqHandler->getAllParameters());
        //var_dump($data);

        $this->pay->setReqContent($this->reqHandler->getGateURL(), $data);
        if ($this->pay->call()) {
            $this->resHandler->setContent($this->pay->getResContent());
            $this->resHandler->setKey($this->reqHandler->getKey());
            if ($this->resHandler->isTenpaySign()) {
                //当返回状态与业务结果都为0时才返回，其它结果请查看接口文档
                if ($this->resHandler->getParameter('status') == 0 && $this->resHandler->getParameter('result_code') == 0) {
                    if ($this->reqHandler->getParameter('service') == 'unified.trade.pay') {
                        return array('token_id' => $this->resHandler->getParameter('token_id'),
                            'services' => $this->resHandler->getParameter('services'),
                            'service' => 'unified.trade.pay');
                    } else if ($this->reqHandler->getParameter('service') == 'pay.weixin.raw.app') {
                        return array('pay_info' => json_decode($this->resHandler->getParameter('pay_info'), true),
                            'out_trade_no' => $this->resHandler->getParameter('out_trade_no'),
                            'transaction_id' => $this->resHandler->getParameter('transaction_id'),
                            'service' => 'pay.weixin.raw.app');
                    }
                } else {
                    return array('status' => 500, 'msg' => 'Error Code:' . $this->resHandler->getParameter('err_code') . ' Error Message:' . $this->resHandler->getParameter('err_msg'));
                }
            }
            return array('status' => 500, 'msg' => 'Error Code:' . $this->resHandler->getParameter('status') . ' Error Message:' . $this->resHandler->getParameter('message'));
        } else {
            return array('status' => 500, 'msg' => 'Response Code:' . $this->pay->getResponseCode() . ' Error Info:' . $this->pay->getErrInfo());
        }
    }


    /**
     * 界面显示
     */
    public function queryRefund()
    {


    }

    /**
     * 订单退款
     */
    public function submitRefund($order)
    {
        $this->reqHandler->setReqParams($order, array('method'));
        $reqParam = $this->reqHandler->getAllParameters();
        if (empty($reqParam['transaction_id']) && empty($reqParam['out_trade_no'])) {
            return array('status' => 500,
                'msg' => '请输入商户订单号或威富通订单号!');
        }
        $this->reqHandler->setParameter('version', $this->cfg->C('version'));
        $this->reqHandler->setParameter('service', 'unified.trade.refund');//接口类型：unified.trade.refund
        $this->reqHandler->setParameter('mch_id', $this->cfg->C('mchId'));//必填项，商户号，由威富通分配
        $this->reqHandler->setParameter('nonce_str', mt_rand(time(), time() + rand()));//随机字符串，必填项，不长于 32 位
        $this->reqHandler->setParameter('sign_type', $this->cfg->C('sign_type'));
        $this->reqHandler->setParameter('op_user_id', $this->cfg->C('mchId'));//必填项，操作员帐号,默认为商户号

        $this->reqHandler->createSign();//创建签名
        $data = Utils::toXml($this->reqHandler->getAllParameters());//将提交参数转为xml，目前接口参数也只支持XML方式

        $this->pay->setReqContent($this->reqHandler->getGateURL(), $data);
        if ($this->pay->call()) {
            $this->resHandler->setContent($this->pay->getResContent());
            $this->resHandler->setKey($this->reqHandler->getKey());
            if ($this->resHandler->isTenpaySign()) {
                //当返回状态与业务结果都为0时才返回支付二维码，其它结果请查看接口文档
                if ($this->resHandler->getParameter('status') == 0 && $this->resHandler->getParameter('result_code') == 0) {
                    /*$res = array('transaction_id'=>$this->resHandler->getParameter('transaction_id'),
                                 'out_trade_no'=>$this->resHandler->getParameter('out_trade_no'),
                                 'out_refund_no'=>$this->resHandler->getParameter('out_refund_no'),
                                 'refund_id'=>$this->resHandler->getParameter('refund_id'),
                                 'refund_channel'=>$this->resHandler->getParameter('refund_channel'),
                                 'refund_fee'=>$this->resHandler->getParameter('refund_fee'),
                                 'coupon_refund_fee'=>$this->resHandler->getParameter('coupon_refund_fee'));*/
                    $res = $this->resHandler->getAllParameters();
                    Utils::dataRecodes('提交退款', $res);
                    return array('status' => 200, 'msg' => '退款成功,请查看result.txt文件！', 'data' => $res);
                } else {
                    return array('status' => 500, 'msg' => 'Error Code:' . $this->resHandler->getParameter('err_code') . ' Error Message:' . $this->resHandler->getParameter('err_msg'));
                }
            }
            return array('status' => 500, 'msg' => 'Error Code:' . $this->resHandler->getParameter('status') . ' Error Message:' . $this->resHandler->getParameter('message'));
        } else {
            return array('status' => 500, 'msg' => 'Response Code:' . $this->pay->getResponseCode() . ' Error Info:' . $this->pay->getErrInfo());
        }
    }

    /**
     * 异步通知方法，demo中将参数显示在result.txt文件中
     */
    public function callback()
    {
        $xml = file_get_contents('php://input');
        $this->resHandler->setContent($xml);
        $this->resHandler->setKey($this->cfg->C('key'));
        if ($this->resHandler->isTenpaySign()) {
            if ($this->resHandler->getParameter('status') == 0 && $this->resHandler->getParameter('result_code') == 0) {
                //echo $this->resHandler->getParameter('status');
                // 11;
                //更改订单状态

                Utils::dataRecodes('接口回调收到通知参数', $this->resHandler->getAllParameters());
                return [
                    'code' => 1,
                    'data' => $this->reqHandler->getAllParameters()
                ];
                echo 'success';
                exit();
            } else {
                return [
                    'code' => 0,
                ];
                echo 'failure1';
                exit();
            }
        } else {
            return [
                'code' => 0
            ];
            echo 'failure2';
        }
    }
}

//$req = new Request();
//$req->index();
?>