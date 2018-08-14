<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/23
 * Time: 13:44
 */

namespace app\app\controller;


use think\Controller;
use think\Log;

class BaseUser extends Controller
{
    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Log::error($_POST);
        if (!self::checkUser(input('user_id'))) {
            exit_json(-1, '用户未登录或用户不存在');
        }
    }

    /**
     * 检验用户是否存在/已登录
     * @param $user_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function checkUser($user_id)
    {
        if (!$user_id) {
            return false;
        }
        $userModel = new \app\common\model\User();
        $res = $userModel->where('id', 'eq', $user_id)->find();
        if ($res) {
            if(!defined('USER_ID')){
                define('USER_ID', input('user_id'));
                //保存用户信息
                define('USER', $res);
            }
            return true;
        } else {
            return false;
        }
    }
}