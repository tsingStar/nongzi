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

        $admin = model('Admins')->where('id', session(config('adminKey')))->find();
        $role = model('Role')->column('role_name', 'id');
        $department = model('Department')->column('name', 'id');
        $this->assign('role', $role);
        $this->assign('depart', $department);
        $this->assign('admin', $admin);
        return $this->fetch();
    }

    /**
     * 获取推广二维码
     */
    public function getQrcode()
    {
        $vip_code = model('Admins')->where('id', session(config('adminKey')))->value('vip_code');
        $spread_url = __URL__."/index/Index/app?vip_code=";
        require_once VENDOR_PATH.'Qrcode/phpqrcode.php';
        $file_name = md5(session(config('adminKey'))).'.png';
        is_dir(__UPLOAD__.'/qrcode') OR mkdir(__UPLOAD__.'/qrcode', 0777, true);
        $save_path = __UPLOAD__.'/qrcode/'.$file_name;
        \QRcode::png($spread_url.$vip_code, $save_path, 1, 50);
        $img_url = __URL__.'/upload/qrcode/'.$file_name;
        exit_json(1, '请求成功', [
            'img_url'=>$img_url,
            'download_url'=>$save_path
        ]);
    }

    /**
     * 下载二维码
     */
    public function downQrcode()
    {
        $file_name = __UPLOAD__.'/qrcode/'.md5(session(config('adminKey'))).'.png';
        downfile($file_name);
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

    /**
     * 更改密码
     */
    public function changePassword()
    {
        if (request()->isAjax()) {
            $admin_id = session(config('adminKey'));
            if ($admin_id) {
                $password = input('password');
                $new_password = input('new_password');
                $r = model('Admins')->where('id', $admin_id)->find();
                if ($r['password'] != md5($password)) {
                    exit_json(-1, '密码错误');
                } else {
                    $res = $r->save(['password' => md5($new_password)]);
                    if ($res) {
                        session('admin', null);
                        exit_json(1, '更改成功');
                    } else {
                        exit_json(-1, '更改失败');
                    }
                }
            } else {
                exit_json(-1, '用户不存在');
            }
        }
        return $this->fetch();
    }


}