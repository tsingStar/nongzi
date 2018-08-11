<?php
/**
 * 用户控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/23
 * Time: 13:44
 */

namespace app\app\controller;


use app\common\model\MoneyLogMonth;
use app\common\model\Pay;
use app\common\model\RongYun;
use app\common\model\SixunOpera;
use think\Log;

class User extends BaseUser
{
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * 获取用户信息
     */
    public function getUserInfo()
    {
        $data = model('user')->formatOne(USER_ID);
        if ($data) {
            exit_json(1, '请求成功', $data);
        } else {
            exit_json(-1, '会员信息不存在');
        }
    }

    /**
     * 添加/编辑收货地址
     */
    public function setAddress()
    {
        $user_name = input('user_name');
        $user_telephone = input('user_telephone');
        $province = input('province');
        $city = input('city');
        $county = input('county');
        $address = input('address');
        $is_default = input('is_default');
        $address_id = input('address_id');
        $data = [
            'user_id' => USER_ID,
            'user_name' => $user_name,
            'user_telephone' => $user_telephone,
            'province' => $province,
            'city' => $city,
            'county' => $county,
            'address' => $address,
            'is_default' => $is_default
        ];
        if ($is_default == 1) {
            model('UserAddress')->where('user_id', USER_ID)->setField('is_default', 0);
        }
        if ($address_id) {
            $res = model('UserAddress')->save($data, ['id' => $address_id]);
        } else {
            $res = model('UserAddress')->insert($data);
        }
        if ($res) {
            exit_json();
        } else {
            exit_json(-1, '添加失败');
        }
    }

    /**
     * 获取用户收获地址列表
     */
    public function getAddress()
    {
        $addressList = model('UserAddress')->field('id address_id, user_name, user_telephone, province, city, county, address, is_default')->where('user_id', USER_ID)->select();
        exit_json(1, '请求成功', $addressList);
    }

    /**
     * 删除配送地址
     */
    public function delAddress()
    {
        $address_id = input('address_id');
        if (!$address_id) {
            exit_json(-1, '参数错误');
        }
        $res = model('UserAddress')->where('id', $address_id)->delete();
        if ($res) {
            exit_json();
        } else {
            exit_json(-1, '参数错误');
        }
    }

    /**
     * 设置默认地址
     */
    public function setDefaultAddress()
    {
        $address_id = input('address_id');
        if (!$address_id) {
            exit_json(-1, '参数错误');
        }
        model('UserAddress')->save(['is_default' => 0], ['user_id' => USER_ID]);
        $res = model('UserAddress')->save(['is_default' => 1], ['id' => $address_id]);
        if ($res) {
            exit_json();
        } else {
            exit_json(-1, '设置默认收获地址失败');
        }
    }

    /**
     * 获取默认收货地址
     */
    public function getDefaultAddress()
    {
        $address = model('UserAddress')->field('id address_id, user_name, user_telephone, province, city, county, address, is_default')->where('user_id', USER_ID)->where('is_default', 1)->find();
        exit_json(1, '请求成功', $address);
    }

    /**
     * 编辑会员信息
     */
    public function editUser()
    {

        $data = input('post.');
        $file = request()->file('headImg');
        if ($file) {
            $headPath = __UPLOAD__ . '/headImg';
            $res = $file->move($headPath, md5(USER_ID));
            $headImg = '/upload/headImg/' . $res->getSaveName() . '?time=' . time();
            $data['head_img'] = $headImg;
        }
        unset($data['user_id']);
        $u = model('user')->where($data)->find();
        if ($u['id'] > 0) {
            exit_json(1, '更新成功');
        }
        $bol = model('user')->allowField(true)->save($data, ['id' => USER_ID]);
        if ($bol) {
            exit_json(1, '更新成功');
        } else {
            exit_json(-1, '保存失败');
        }
    }

    /**
     * 绑定手机
     */
    public function lockMob()
    {
        $mob = input('telephone');
        $code = input('code');
        if (!$mob || !$code) {
            exit_json(-1, '参数错误');
        }
        $sms = model('SmsLog')->where(['create_time' => ['gt', time() - 30], 'status' => 0, 'telephone' => $mob, 'type' => 4])->find();
        if ($sms['code'] != $code) {
            exit_json(-1, '验证码错误');
        } else {
            $sms->save(['status' => 1]);
            $res = model('User')->save(['lock_mob' => $mob], ['id' => USER_ID]);
            if ($res) {
                exit_json();
            } else {
                exit_json(-1, '绑定失败');
            }
        }
    }


    //TODO 待处理

    /**
     *  获取账单详情
     */
    public function getMoneyLog()
    {
        $page = input('page') ? input('page') : 1;
        $pageNum = input('pageNum') ? input('pageNum') : 20;
        $offset = ($page - 1) * $pageNum;
        $list = model('money_log')->where('user_id', USER_ID)->order('create_time desc')->limit($offset, 20)->select();
        $data = [];
        foreach ($list as $value) {
            $temp['order_no'] = $value['order_no'];
            $temp['desc'] = $value['desc'];
            $temp['create_time'] = $value['create_time'];
            $temp['month'] = date('m', strtotime($value['create_time']));
            $temp['money'] = '￥ ' . $value['money'];
            $temp['type'] = $value['type'];
            $moneyAll = MoneyLogMonth::getMonthMoney(date('Y-m', strtotime($value['create_time'])), USER_ID);
            $temp = array_merge($temp, $moneyAll);
            $data[] = $temp;
        }
        exit_json(1, '请求成功', $data);
    }

    /**
     * 获取账单详情
     */
    public function getMoneyDetail()
    {
        $order_no = input('order_no');
        $order = model('order')->where('order_no', $order_no)->find();
        if (!$order) {
            $order = model('charge_order')->where('order_no', $order_no)->find();
        }
        if ($order) {
            $data['title'] = isset($order['shop_name']) ? $order['shop_name'] . '-订单支付' : '余额充值';
            $data['money'] = isset($order['real_cost']) ? $order['real_cost'] : $order['money'];
            $data['pay_type'] = config('pay_type')[$order['pay_type']];
            $data['pay_time'] = $order['pay_time'];
            $data['create_time'] = $order['create_time'];
            $data['order_no'] = $order_no;
            exit_json(1, '请求成功', $data);
        } else {
            exit_json(-1, '订单信息不存在');
        }


    }

    /**
     * 获取联系我们
     */
    public function getContact()
    {
        $shop_id = input('shop_id');
        $shop_phone = model('shop')->where('id', $shop_id)->value('phone');
        $data = db('web_contact_us')->find();
        exit_json(1, '请求成功', [
            'phone' => $data['telephone'],
            'complaints_phone' => $data['complaints_phone'],
            'shop_phone' => $shop_phone
        ]);
    }

}