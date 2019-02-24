<?php
/**
 * Created by PhpStorm.
 * User: tsing
 * Date: 2019/1/9
 * Time: 21:42
 */

namespace app\agent\controller;


use app\admin\model\Admins;
use app\common\model\SendSms;
use think\Controller;
use think\Log;

class Pub extends Controller
{
    protected function _initialize()
    {
        parent::_initialize();
    }

    public function login()
    {
        if (request()->isAjax()) {
            $type = input("type");
            //1 短信登录 2 用户名密码登录
            $telephone = input("telephone");
            $vcode = input("vcode");
            if ($type == 1) {
                $sm = model('SmsLog')->where(['telephone' => $telephone, 'type' => '10001', 'code' => $vcode, "status" => 0])->find();
                if ($sm) {
                    $sm->save(["status" => 1]);
                    $admin = Admins::get(["telephone" => $telephone]);
                } else {
                    exit_json(-1, "验证码错误");
                }
            } elseif ($type == 2) {
                $admin = Admins::get(["uname" => $telephone]);
            } else {
                exit_json(-1, "参数错误");
            }
            if (!$admin) {
                exit_json(-1, "代理商不存在");
            } else {
                if (!in_array(15, explode(",", $admin["role_id"]))) {
                    exit_json(-1, "抱歉，您还不是代理商");
                }
                if($admin["password"] != md5($vcode)){
                    exit_json("密码错误");
                }
                session("agent_id", $admin["id"]);
                exit_json();
            }
        } else {
            return $this->fetch();
        }
    }

    /**
     * 获取验证码
     */
    public function getSms()
    {
        $telephone = input('telephone');
        if (!test_tel($telephone)) {
            exit_json(-1, '手机号不合法');
        }
//        if($telephone != "15554832010"){
            $admins = new Admins();
            $admin = $admins->where('telephone', $telephone)->find();
            Log::error($admin);
            if (!$admin) {
                exit_json(-1,"代理商用户不存在");
            }
            if (!in_array(15, explode(",", $admin["role_id"]))) {
                exit_json(-1,"抱歉，你还不是代理商");
            }
//        }
        exit_json();
        $code = rand(100000, 999999);
        $remain_time = '2分钟';
        $content = urlencode("$code##$remain_time");
        $sendSms = new SendSms($telephone, config('sms.tempId'), $content);
        $res = $sendSms->sendVcode();
        if ($res) {
            model('SmsLog')->save(['telephone' => $telephone, 'type' => '10001', 'code' => $code]);
            exit_json(1, '验证码发送成功');
        } else {
            exit_json(-1, $sendSms->getError());
        }
    }

}