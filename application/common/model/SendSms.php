<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/22
 * Time: 9:31
 */

namespace app\common\model;


class SendSms
{
    private $base_url='http://api.1cloudsp.com/api/v2/single_send';
    private $accesskey;
    private $secret;
    private $signId;
    private $error;
    private $phone;
    private $tempId;
    private $content;
    private $url;

    public function __construct($phone, $tempId='', $content)
    {
        $this->phone = $phone;
        $this->tempId = $tempId;
        $this->accesskey = config('sms.accesskey');
        $this->secret = config('sms.secret');
        $this->signId = config('sms.sign');
        $this->content = $content;
    }

    private function build_url(){
        $this->url = $this->base_url.'?accesskey='.$this->accesskey.'&secret='.$this->secret.'&sign='.$this->signId.'&templateId='.$this->tempId.'&mobile='.$this->phone.'&content='.$this->content;
    }

    /**
     * 发送验证码
     * @return bool
     */
    public function sendVcode()
    {
        $this->build_url();
        $res = file_get_contents($this->url);
        $res = json_decode($res, true);
        if ($res['code'] == 0) {
            return true;
        } else {
            $this->error = $res['msg'];
            return false;
        }
    }

    /**
     * 检验验证码
     * @param $vcode
     * @return bool
     */
    public function checkVcode($vcode)
    {
        $res = $this->ServerApi->verifycode($this->phone, $vcode);
        if($res['code'] === 200){
            return true;
        }else{
            $this->error = $res['code'];
            return false;
        }
    }

    public function getError()
    {
        return $this->error;
    }
}