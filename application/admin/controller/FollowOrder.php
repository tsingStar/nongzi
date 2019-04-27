<?php
/**
 * 平台会员管理
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-07-03
 * Time: 10:17
 */

namespace app\admin\controller;


use app\common\model\FollowCate;

class FollowOrder extends BaseController
{
    protected function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 跟单记录
     */
    public function followList()
    {
        $param = input("get.");
        $model = model("FollowOrder")->alias("a");
        if (isset($param['start_time']) && $param["start_time"] != "") {
            $model->where("a.create_time", "gt", strtotime($param["start_time"]));
        }
        if (isset($param['end_time']) && $param["end_time"] != "") {
            $model->where("a.create_time", "lt", strtotime($param["end_time"]) + 86400);
        }
        $list = $model->join("Admins b", "a.admin_id=b.id", "left")->join("FollowCate c", "a.cate_id=c.id", "left")->join("User d", "a.user_id=d.id", "left")->field("a.*, b.name admin_name, c.name cate_name, d.user_name")->paginate(20, false, ["query"=>$param]);
        $this->assign("param", $param);
        $this->assign("cateList", $list);
        return $this->fetch();
    }



    /**
     * 跟单分类
     */
    public function followCate()
    {
        $cateList = db("follow_cate")->select();
        $this->assign("cateList", $cateList);
        return $this->fetch();
    }

    /**
     * 删除跟单记录
     */
    public function delFollow()
    {
        exit_json();
    }

    /**
     * 添加跟单分类
     */
    public function addFollowCate()
    {
        $id = input("id");
        $cate = db("follow_cate")->where("id", $id)->find();
        $this->assign("cate", $cate);
        return $this->fetch("addFollowCate");
    }

    /**
     * 保存跟单分类
     */
    public function saveCate()
    {
        $data = input("post.");
        $model = new FollowCate();
        if($data["id"] == 0){
            //新增
            $exist = FollowCate::get(["name"=>$data["name"]]);
            if($exist){
                exit_json(-1, "分类已经存在");
            }
            unset($data["id"]);
            $res = $model->allowField(true)->save($data);
            if($res){
                exit_json();
            }else{
                exit_json(-1, "添加失败");
            }
        }else{
            //更新
            $cate = $model->where("id", $data["id"])->find();
            if($cate){
                $res = $cate->allowField(true)->save($data);
                if($res){
                    exit_json();
                }else{
                    exit_json(-1, "更新失败");
                }
            }else{
                exit_json(-1, "分类不存在");
            }
        }

    }
    /**
     * 删除跟单分类
     */
    public function delFollowCate()
    {
        $id = input("ids");
        $cate = FollowCate::get($id);
        if($cate){
            if($cate->delete()){
                exit_json();
            }else{
                exit_json(-1, "删除失败");
            }

        }else{
            exit_json(-1, "分类不存在，稍后重试");
        }
        return $this->fetch();
    }

}