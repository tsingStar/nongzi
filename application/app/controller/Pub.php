<?php
/**
 * App用户通用类
 * Created by PhpStorm.
 * User: tsingStar
 * Date: 2018/1/19
 * Time: 8:50
 */

namespace app\app\controller;


use app\common\model\SendSms;
use app\common\model\User;
use think\Controller;

class Pub extends Controller
{
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub

    }


    public function getVersion()
    {
        $version = input('version');
        if ($version == config('version')) {
            exit_json(-1, '当前版本为最新版本');
        } else {
            exit_json(1, '有新版本', ['url' => config('download_url'), 'version' => config('version')]);
        }
    }

    /**
     *
     * 会员登陆
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    function login()
    {

        $telephone = input('telephone');
        $password = input('password');
        $userModel = new User();
        $user = $userModel->where('telephone', 'eq', $telephone)->find();
        if ($user) {
            if ($user['password'] != md5($password)) {
                exit_json(-1, '密码错误');
            }
            exit_json(1, '登陆成功', $userModel->formatOne($user['id']));
        } else {
            exit_json(-1, '会员不存在');
        }
    }

    /**
     * 验证码登陆
     */
    public function loginByCode()
    {
        $telephone = input('telephone');
        $code = input('code');
        $vcode = model('SmsLog')->where(['create_time' => ['gt', time() - 30], 'status' => 0, 'telephone' => $telephone, 'type' => 3])->value('code');
        if ($code != $vcode) {
            exit_json(-1, '验证码错误');
        }
        $userModel = new User();
        $user = $userModel->where('telephone', $telephone)->find();
        if ($user) {
            exit_json(1, '登陆成功', $userModel->formatOne($user['id']));
        }
    }

    /**
     * 会员注册
     */
    function register()
    {
        $telephone = input('telephone');
        $password = input('password');
        $vcode = input('code');
        $vip_code = input('vip_code');
        $user = model('user')->where('telephone', $telephone)->find();
        if ($user) {
            exit_json(-1, '手机号已注册');
        } else {

            $sms = model('SmsLog')->where(['create_time' => ['gt', time() - 30], 'status' => 0, 'telephone' => $telephone, 'type' => 1])->find();
            if ($sms['code'] != $vcode) {
                exit_json(-1, '验证码错误');
            }
            $res = model('user')->isUpdate(false)->save(['telephone' => $telephone, 'password' => md5($password), 'vip_code' => $vip_code, 'user_name' => uniqid()]);
            if ($res) {
                $sms->save(['status' => 1]);
                $user_id = model('user')->getAttr('id');
                exit_json(1, '注册成功', model('user')->formatOne($user_id));
            } else {
                exit_json(-1, '注册失败');
            }
        }
    }

    /**
     * 校验验证码
     */
    function verifyCode()
    {
        $data = input('post.');
        if (!$data['telephone'] || !$data['code']) {
            exit_json(-1, '参数错误');
        }
        $sms = model('SmsLog')->where(['create_time' => ['gt', time() - 30], 'status' => 0, 'telephone' => $data['telephone'], 'type' => 2])->find();
        if ($sms['code'] != $data['code']) {
            exit_json(-1, '验证码错误');
        } else {
            $sms->save(['status' => 1]);
            exit_json();
        }
    }

    /**
     * 重置密码
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function resetPassword()
    {
        $data = input('post.');
        $userModel = new User();
        $user = $userModel->where(['telephone' => $data['telephone']])->find();
        $res = $user->save(['password' => md5($data['password'])]);
        if ($res) {
            exit_json(1, '重置密码成功');
        } else {
            exit_json(-1, '重置密码失败');
        }

    }

    /**
     * 获取验证码
     */
    function getVcode()
    {
        //sendType  1 注册 2 忘记密码 3 短信验证码登陆 4 绑定手机
        $telephone = input('telephone');
        $sendType = input('sendType');
        if (!test_tel($telephone)) {
            exit_json(-1, '手机号不合法');
        }
        $user = model('user')->where('telephone', $telephone)->find();
        switch ($sendType) {
            case 1:
                if ($user) {
                    exit_json(-1, '手机号已注册过');
                }
                break;
            case 2:
                if (!$user) {
                    exit_json(-1, '手机号未注册过');
                }
                break;
            case 3:
                if (!$user) {
                    exit_json(-1, '手机号未注册，请注册后登陆');
                }
                break;
            case 4:
                $lock = model('User')->where('lock_mob', $telephone)->find();
                if ($lock) {
                    exit_json(-1, '手机号已被绑定');
                }
            default:
                exit_json(-1, '参数错误');
        }
        $code = rand(100000, 999999);
        $remain_time = '30秒';
        $content = urlencode("$code##$remain_time");
        $sendSms = new SendSms($telephone, config('sms.tempId'), $content);
        $res = $sendSms->sendVcode();
        if ($res) {
            model('SmsLog')->save(['telephone' => $telephone, 'type' => $sendType, 'code' => $code]);
            exit_json(1, '验证码发送成功');
        } else {
            exit_json(-1, $sendSms->getError());
        }
    }



    //TODO 待处理

    /**
     * 微信小程序登陆
     */
    public function loginByWeiXin()
    {
        $app_id = input('app_id');
        $app_secret = input('app_secret');
        $js_code = input('js_code');
        $encryptedData = input('encryptedData');
        $iv = input('iv');
        if (!$app_id || !$app_secret || $js_code) {
            exit_json(-1, '参数错误');
        }

        //返回数据格式
//            {
//                  "openid": "OPENID",
//                  "session_key": "SESSIONKEY"
//                  "expires_in": 2592000
//            }
        $res = file_get_contents("https://api.weixin.qq.com/sns/jscode2session?appid=$app_id&secret=$app_secret&js_code=$js_code&grant_type=authorization_code");
        $res = json_decode($res, true);

        if (!$res['openid']) {
            exit_json(-1, '参数错误');
        }
        $session_key = $res['session_key'];
        $open_id = $res['openid'];
        if (!session('session_key')) {
            session('session_key', $session_key);
        }
        include VENDOR_PATH . 'WeiXin/wxBizDataCrypt.php';
        $pc = new \WXBizDataCrypt($app_id, $session_key);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);

        if ($errCode == 0) {


            print($data . "\n");
        } else {
            print($errCode . "\n");
        }
    }

    /**
     * 绑定手机号
     */
    public function bindTelephone()
    {
        $iv = input('iv');
        $encryptedData = input('encryptedData');
        $session_key = session('session_key');
        include VENDOR_PATH . 'WeiXin/wxBizDataCrypt.php';
        $pc = new \WXBizDataCrypt($app_id, $session_key);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        if($errCode == 0){
            //请求成功
        }else{
            //请求失败

        }



    }


}