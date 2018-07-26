<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/19
 * Time: 15:09
 */

namespace app\admin\controller;


class Shop extends BaseController
{
    private $shopModel;

    public function __construct()
    {
        $this->shopModel = new \app\common\model\Shop();
        parent::__construct();
    }

    /**
     * 获取店铺列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $map = [];
        if(input('shopname')){
            $map['shopname'] = ['like', '%'.input('shopname').'%'];
        }
        $list = $this->shopModel->where($map)->select();
        $this->assign('list', $list);
        return $this->fetch();
    }

    /**
     * 添加店铺
     * @return mixed
     */
    public function shopAdd()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            $data['password'] = md5($data['password']);
            $res = $this->shopModel->allowField(true)->save($data);
            if ($res) {
                exit_json();
            } else {
                exit_json(-1, '添加失败');
            }
        }
        return $this->fetch();
    }

    /**
     * 更改店铺状态
     */
    function changeStatus()
    {
        $shopId = input('id');
        $status = input('status');
        $res = $this->shopModel->save(['enable'=>$status], ['id'=>$shopId]);
        if($res){
            exit_json();
        }else{
            exit_json(-1, '更新失败');
        }
    }

    function del()
    {
        $id = input('id');
        $res = $this->shopModel->where('id', 'eq', $id)->delete();
        if($res){
            exit_json();
        }else{
            exit_json(-1, '删除失败');
        }
    }

    /**
     * 登陆指定店铺
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function accessByAdmin()
    {
        $shopId = input('shopId');
        $shop = $this->shopModel->where('id', 'eq', $shopId)->find();
        if (session(config('adminkey')) == 1) {
            if($shop['id']>0){
                session(config('shopkey'), $shopId);
                $this->redirect(url("shop/Index/index"));
            }else{
                $this->error('店铺不存在');
            }
        }else{
            $this->error('参数错误');
        }

    }

    /**
     * 检验是否重名
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function checkName()
    {
        $uname = input('uname');
        $shopId = input('shopId');
        $where = [
            'uname' => $uname
        ];
        if ($shopId > 0) {
            $where['id'] = ['neq', $shopId];
        }
        $res = $this->shopModel->where($where)->find();
        if ($res) {
            return 'false';
        } else {
            return 'true';
        }
    }

    /**
     * 设置默认店铺
     */
    public function set_default()
    {
        $id = input('id');
        $res = model('shop')->save(['is_default'=>0], ['id'=>['neq', $id]]);
        $res1 = model('shop')->save(['is_default'=>1], ['id'=>$id]);
        if($res && $res1){
            exit_json();
        }else{
            exit_json(-1, '设置成功');
        }
        
    }


}