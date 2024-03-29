<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-07-10
 * Time: 11:52
 */

namespace app\index\controller;


use app\common\model\SendSms;
use think\Controller;
use think\Log;

class Interval extends Controller
{

    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub

    }
    /**
     * 发送超时未支付订单
     */
    public function sendChaoShiOrder()
    {
        ignore_user_abort(true);
        Log::error("okokokokokoko");
        exit;
        $c = db("base_info")->find();
        $time = unserialize($c['config'])['chaoshi_order'];
        $list = model("Order")->alias("a")->join("User b", "a.user_id=b.id")->where("a.is_trash", 0)->where("a.create_time", "gt", time() - $time*60)->where("a.create_time", "lt", time()+60-$time*60)->where("a.pay_status", 0)->field("a.*, b.telephone")->select();
        foreach ($list as $item){
            $telephone = $item['receiver_telephone'];
            $order_no = strlen($item['order_no'])>20?(substr($item['order_no'], 0, 8)."****".substr($item['order_no'], -8)):$item['order_no'];
            $content = urlencode($order_no);
            $sendSms = new SendSms($telephone, config('sms.payId'), $content);
            $res = $sendSms->sendVcode();
            if ($res) {
                model('SmsLog')->save(['telephone' => $telephone, 'type' => 1001]);
            }
        }
    }


    public function index()
    {
        ignore_user_abort(true);
        set_time_limit(0);
        Log::error('1231');
        echo 'a';
//        $this->sec_tips();
//        $this->checkOrderStatus();
//        $this->downGoodsProp();
        echo 'b';

    }

    /**
     * 设置提醒
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function sec_tips()
    {
        ignore_user_abort(true);
        set_time_limit(0);

        Log::error('aa');
        $i = 1;
        do {
            $list = db('sec_tips')->where(['tips_time' => ['elt', time() + 5 * 60], 'status' => 0])->select();
            $token = [];
            foreach ($list as $l) {
                $t = model('user')->where('id', $l['user_id'])->value('jiguangToken');
                $token[] = $t;
            }
            if (count($token) > 0) {
                //pushMess('你设置的抢购活动还有5分钟开启', ['id'=>'', 'url'=>'', 'scene'=>'sec_active'], [
//                    "registration_id" => $token]);
                db('sec_tips')->where('tips_time', 'elt', time() + 5 * 60)->update(['status' => 1]);
            }
            sleep(4);
            $i++;
            Log::error($i.'a');
        } while (true);
    }

    /**
     * 检验订单有效期
     */
    public function checkOrderStatus()
    {
        ignore_user_abort(true);
        set_time_limit(0);
        $j = 1;
        do{
            model('order')->save(['order_status' => 3], ['create_time' => ['lt', time() - config('order_remain') * 60], 'pay_status' => 0]);
            sleep(5);
            $j++;
            Log::error($j.'b');
        }while(true);
    }

    public function downGoodsProp()
    {
        ignore_user_abort(true);
        set_time_limit(0);
        $t = 1;
        do{
            model('goods_prop')->where('create_time', 'lt', strtotime(date('Y-m-d')))->setField('num', 0);
            $t++;
            Log::error($t.'c');
            sleep(7);
        } while(true);
    }
}