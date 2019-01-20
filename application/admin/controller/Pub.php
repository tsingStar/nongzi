<?php
/**
 * 后台公共登陆模块
 * Created by PhpStorm.
 * User: tsingStar
 * Date: 2018/1/9
 * Time: 16:22
 */

namespace app\admin\controller;


use app\admin\model\Admins;
use think\Controller;

class Pub extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->assign('sitename', config('sitename'));
    }

    /**
     * 后台用户登陆
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    function login(){
        if(request()->isAjax()){
//            $captch = input('post.captch');
            //添加登陆记录
            addAdminLoginLog();
            $name = input('post.name');
            $password = input('post.password');
            $remember = input('post.online');
//            if(!captcha_check($captch)){
//                json('验证码错误', -1);
//            }
            $adminModel = new Admins();
            if($admin = $adminModel->checkAdmin($name, $password)){
                if(!$admin['enable']){
                    exit_json(-1, '用户被禁止使用，请联系管理员');
                }
                if($remember){
                    cookie('admin_id', $admin['id']);
                    cookie('admin_pwd', $admin['password']);
                }
                set_admin_login($admin);
                exit(json_encode(['code'=>200, 'msg'=>'登陆成功']));
            }else{
                exit(json_encode(['code'=>-1, 'msg'=>'用户名或密码错误']));
            }
        }else{
            return view('login');
        }
    }

    /**
     * 删除图片
     */
    public function dropPic()
    {
        if(!session(config('adminKey'))){
            exit;
        }
        $path = input('post.path');
        $relPath = __PUBLIC__.$path;
        delfile($relPath);
    }

    /**
     * @param string $name
     * 上传图片
     */
    public function uploadImg()
    {
        $file = request()->file('file');
        if ($file) {
            $info = $file->move(__UPLOAD__);
            if ($info) {
                $saveName = $info->getSaveName();
                $path = "/upload/" . $saveName;
                exit_json(1, '操作成功', $path);
            } else {
                // 上传失败获取错误信息
                exit_json(-1, $file->getError());
            }
        }else{
            exit_json(-1, '文件不存在');
        }
    }

    public function excel()
    {
        $list = model("Product")->where("is_up", 1)->select();
        $plist = [];
        foreach ($list as $item){
            $prop_list= model("ProductAttr")->where("product_id", $item["id"])->field("prop_value_name, price_one, price_comb")->select();
//            $prop = [];
//            foreach ($prop_list as $v){
//                $t[] = $v['prop_value_name'];
//                $t[] = $v['price_one'];
//                $t[] = $v['price_comb'];
//                $prop[] = $t;
//            }
            $t = [
                $item['id'],
                $item['name'],
                $item['bname'],
                $prop_list
            ];
            $plist[] = $t;
        }
        $this->assign("list", $plist);
        return $this->fetch();
//        $header = [
//            "产品id",
//            "产品名称",
//            "产品别称",
//            "产品规格详情"
//        ];
//        excel($header, $plist, date("Y-m-d"));

    }
}