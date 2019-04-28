<?php
/**
 * 管理员
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/8
 * Time: 15:09
 */

namespace app\admin\controller;


use app\admin\model\Admins;

class Admin extends BaseController
{
    private $adminModel;
    private $roleModel;
    public function __construct()
    {
        parent::__construct();
        $this->adminModel = new Admins();
        $this->roleModel = new \app\admin\model\Role();
        $roleList = $this->roleModel->column('role_name', 'id');
        $this->assign('roleList', $roleList);
    }

    /**
     * 管理员用户列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    function adminList()
    {

        $map = [];
        if(input('get.mintime')){
            $map['create_time'][] = ['>', strtotime(input('get.mintime'))];
        }
        if(input('get.maxtime')){
            $map['create_time'][] = ['<', strtotime(input('get.maxtime'))];
        }
        if( isset($map['create_time']) && count($map['create_time'])==1){
            $map['create_time'] = $map['create_time'][0];
        }
        if(input('get.uname')){
            $map['uname'] = input('get.uname');
        }
        $adminList = $this->adminModel->getAdminList($map);
        foreach ($adminList as $l){
            $one_name = model('Department')->where('id', $l['department_pid'])->value('name');
            $two_name = model('Department')->where('id', $l['department_id'])->value('name');
            $l['depart'] = $one_name.'-'.$two_name;
        }
        $this->assign('list', $adminList);
        return $this->fetch();
    }

    /**
     * 管理员添加
     * @return mixed
     */
    function adminAdd()
    {
        if(request()->isAjax()){
            $data = input('post.');
            $role_id_arr = $data['role_id'];
            $data['role_id'] = join(',', $data['role_id']);
            $data['password'] = md5($data['password']);
            $check = $this->validate($data, 'Admin');
            if(true !== $check){
                exit_json(-1, $check);
            }
            if($data["vip_code"] != ""){
                $re = model("Admins")->where("vip_code", $data["vip_code"])->find();
                if($re){
                    exit_json(-1, "邀请码重复,请重新填写或留空使用系统随机生成");
                }
            }
            $res = $this->adminModel->allowField(true)->isUpdate(false)->save($data);
            if($res){
                $id = $this->adminModel->getLastInsID();
                if(in_array(15, $role_id_arr)){
                    model("AgentLog")->save([
                        "admin_id"=>session("admin_id"),
                        "name"=>"添加代理商",
                        "data"=>"代理商id:".$id.";用户名：".$data["uname"].";姓名：".$data["name"]
                    ]);
                }
                if($data["vip_code"] == ""){
                    $vip_code = getRandStr($id, 5, 'nongzi');
                    model('Admins')->save(['vip_code'=>$vip_code], ['id'=>$id]);
                }
                exit_json(1, '保存成功');
            }else{
                exit_json(-1, '保存失败');
            }
        }else{
            $area_province = getArea();
            $this->assign("province", $area_province);
            $dep = model('Department')->select();
            $dep_list = getTree($dep, 0);
            $this->assign('dep_list', json_encode($dep_list));
            return $this->fetch();
        }
    }

    public function getArea()
    {
        $pid = input("pid");
        $list = getArea($pid);
        exit_json(1, "", $list);
    }

    /**
     * 管理员编辑
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    function adminEdit()
    {
        if(request()->isAjax()){
            $data = input('post.');
            $role_id_arr = $data['role_id'];
            $data['role_id'] = join(',', $data['role_id']);

            if($data["vip_code"] != ""){
                $re = model("Admins")->where("vip_code", $data["vip_code"])->where("id", 'neq', $data["id"])->find();
                if($re){
                    exit_json(-1, "邀请码重复,请重新填写或留空使用系统随机生成");
                }
            }
            $vip_code = model("Admins")->where("id", $data["id"])->value("vip_code");
            model("User")->save(["vip_code"=>$data["vip_code"]], ["vip_code"=>$vip_code]);
            $res = $this->adminModel->allowField(['role_id', 'uname', 'describe', 'name', 'department_id', 'department_pid', 'telephone', 'vip_code', 'province_id', 'city_id', 'country_id', 'agent_level', 'person_money', 'first_order', 'agent_cate'])->save($data, ['id'=>$data['id']]);
            if($res){
                if(in_array(15, $role_id_arr)){
                    model("AgentLog")->save([
                        "admin_id"=>session("admin_id"),
                        "name"=>"添加代理商",
                        "data"=>"代理商id:".$data['id'].";用户名：".$data["uname"].";姓名：".$data["name"]
                    ]);
                }
                exit_json(1, '保存成功');
            }else{
                exit_json(-1, '保存失败');
            }
        }else{
            $admin = $this->adminModel->where('id', 'eq', input('adminId'))->find();
            $roleArr = explode(',', $admin['role_id']);
            $dep = model('Department')->select();
            $dep_list = getTree($dep, 0);
            $province = getArea();
            $city = getArea($admin["province_id"]);
            $country = getArea($admin["city_id"]);
            $this->assign("area", ["province"=>$province, "city"=>$city, "country"=>$country]);
            $this->assign('dep_list', json_encode($dep_list));
            $this->assign('admin', $admin);
            $this->assign('roleArr', $roleArr);
            return $this->fetch();
        }
    }

    /**
     * 检验名字是否存在
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    function checkName()
    {
        $name = input('uname');
        $adminId = input('id');
        $map['uname'] = $name;
        if($adminId){
            $map['id']=['neq', $adminId];
        }
        $admin = $this->adminModel->where($map)->find();
        if($admin){
            echo "false";
        }else{
            echo "true";
        }
    }

    function del(){
        $adminId = input('id');
        $res = $this->adminModel->where('id', 'eq', $adminId)->delete();
        if($res){
            exit_json();
        }else{
            exit_json(-1, '删除失败');
        }
    }

    /**
     * 更改管理员状态
     */
    public function changeStatus()
    {
        $id = input('id');
        $enable = input('enable');
        $res = model('admins')->save(['enable'=>$enable], ['id'=>$id]);
        if($res){
            exit_json(1, '更新成功');
        }else{
            exit_json(1, '更新失败');
        }
    }


}