<?php
/**
 * 后台管理主页面
 * Created by PhpStorm.
 * User: tsingStar
 * Date: 2018/1/9
 * Time: 15:10
 */

namespace app\admin\controller;

use app\admin\model\Role;

class Index extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 后台主导航页
     * @return mixed|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    function index()
    {
        $role = new Role();
        $admin = session('admin');
        $admin['roleName'] = $role->getRoleName(session('role_id'));
        if (session('role_id') > 0) {
            $menuModel = new \app\admin\model\Menu();
            $nodeList = $role->getNodeList(session('role_id'));
            $navList = $menuModel->getNavList($nodeList);
        } else {
            return $this->fetch('pub/404');
        }
        $navList = getMenu($navList);
        $this->assign('admin', $admin);
        $this->assign('navList', $navList);
        return view('index');
    }

    /**
     * 我的首页
     * @return mixed
     */
    function shouye()
    {
//        $order_num = model('order')->count();
//        $order_num_today = model('order')->where(['create_time' => ['between', [strtotime(date('Y-m-d')), strtotime(date('Y-m-d', strtotime('+1 day')))]]])->count();
//        $order_num_yes = model('order')->where(['create_time' => ['between', [strtotime(date('Y-m-d', strtotime('-1 day'))), strtotime(date('Y-m-d'))]]])->count();
//        $vip_num = model('user')->count();
//        $vip_num_today = model('user')->where(['creattime' => ['between', [strtotime(date('Y-m-d')), strtotime(date('Y-m-d', strtotime('+1 day')))]]])->count();
//        $vip_num_yes = model('user')->where(['creattime' => ['between', [strtotime(date('Y-m-d', strtotime('-1 day'))), strtotime(date('Y-m-d'))]]])->count();
//        $this->assign('data', ['order_num'=>$order_num, 'order_num_today'=>$order_num_today, 'order_num_yes'=>$order_num_yes,'vip_num'=>$vip_num, 'vip_num_today'=>$vip_num_today, 'vip_num_yes'=>$vip_num_yes]);
//        $admin = model('admins')->where('id', session('admin_id'))->find();
//        $this->assign('admin', $admin);
//        return $this->fetch();
        echo phpinfo();
        exit;
    }

    /**
     * 用户退出
     */
    function logout()
    {
        session(null);
        cookie(null);
        exit_json(1, '退出成功');
    }


}