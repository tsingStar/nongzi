<?php
/**
 * Created by PhpStorm.
 * User: tsing
 * Date: 2019/4/28
 * Time: 21:47
 */

namespace app\admin\controller;


use think\Log;

class Content extends BaseController
{
    protected function _initialize()
    {
        parent::_initialize();
    }

    /**
     * app启动图
     */
    public function appLogo()
    {

        $list = model("LoginLogo")->order('is_show desc, update_time desc')->paginate(15);
        $this->assign("list", $list);
        return $this->fetch('appLogo');
    }

    /**
     * 添加logo图
     */
    public function logoAdd()
    {
        $id = input("id");
        $logo = model("LoginLogo")->where("id", $id)->find();
        if(request()->isAjax()){
            if(!$logo){
                $res = model("LoginLogo")->allowField(true)->except("id")->save(input("post."));
            }else{
                $res = $logo->allowField(true)->save(input('post.'));
            }
            if($res){
                exit_json();
            }else{
                exit_json(-1, '操作失败');
            }
        }else{
            $this->assign("item", $logo);
            return $this->fetch();
        }
    }

    public function delLogo()
    {
        $id = input("ids");
        if(model('LoginLogo')->where("id", $id)->delete()){
            exit_json();
        }else{
            exit_json(-1, "删除失败");
        }
    }
    /**
     * 更改展示
     */
    public function changeLogoStatus()
    {
        $id = input("id");
        $is_show = input("is_show");
        model("LoginLogo")->where("id", "neq", $id)->setField('is_show', 0);
        model("LoginLogo")->where("id", $id)->setField('is_show', $is_show);
        exit_json();
    }
    /**
     * 更改展示
     */
    public function changeIndexStatus()
    {
        $id = input("id");
        $is_show = input("is_show");
        db("adv")->where("id", $id)->update(['is_show'=>$is_show]);
        exit_json();
    }

    /**
     * 推送消息
     */
    public function tips()
    {

        $list = model("tips")->order("create_time desc")->paginate(15);
        foreach ($list as &$item){
            $item['cate'] = $item['cate'] == 1?'系统通知':($item['cate'] == 2?'客服通知':'活动通知');
        }
        $this->assign("list", $list);
        return $this->fetch("tips");
    }

    /**
     * 添加logo图
     */
    public function tipsAdd()
    {
        $id = input("id");
        $logo = model("Tips")->where("id", $id)->find();
        if(request()->isAjax()){
            if(!$logo){
                $res = model("tips")->allowField(true)->except("id")->save(input("post."));
            }else{
                $res = $logo->allowField(true)->save(input('post.'));
            }
            if($res){
                exit_json();
            }else{
                exit_json(-1, '操作失败');
            }
        }else{
            $this->assign("item", $logo);
            return $this->fetch();
        }
    }
    /**
     * 推送消息
     */
    public function adv()
    {

        $list = db("adv")->order("id desc")->paginate(15);
        $this->assign("list", $list);
        return $this->fetch("adv");
    }

    /**
     * 添加logo图
     */
    public function advAdd()
    {
        $id = input("id");
        $logo = db("adv")->where("id", $id)->find();
        if(request()->isAjax()){
            if(!$logo){
                $res = db("adv")->insert([
                    'title'=>input('title'),
                    'image'=>input('image'),
                    'content'=>input('content')
                ]);
            }else{
                $res = db('adv')->where("id", $id)->update([
                    'title'=>input('title'),
                    'image'=>input('image'),
                    'content'=>input('content')
                ]);
            }
            if($res){
                exit_json();
            }else{
                exit_json(-1, '操作失败');
            }
        }else{
            $this->assign("item", $logo);
            return $this->fetch();
        }
    }

    public function delAdv()
    {
        $id = input("id");
        if(db('adv')->where("id", $id)->delete()){
            exit_json();
        }else{
            exit_json(-1, "删除失败");
        }
    }
    public function delTips()
    {
        $id = input("id");
        if(model('tips')->where("id", $id)->delete()){
            exit_json();
        }else{
            exit_json(-1, "删除失败");
        }
    }

    public function sendTips()
    {
        $status = input("status");
        $id = input("id");
        $tip = model("tips")->where("id", $id)->find();
        if($status != 1 && $status != 2){
            exit_json(-1, "参数错误");
        }
        set_time_limit(0);
        ignore_user_abort(true);
        if($status == 1){
            $user_list = model("user")->field("id, register_id")->select();
        }
        if($status == 2){
            $uids = input("uids");
            $id_arr = array_filter(explode(",", $uids));
            $user_list = model("user")->where("id", "in", $id_arr)->field("id, register_id")->select();
        }
        $insert_data = [];
        $push_data = [];
        foreach ($user_list as $u){
            $insert_data[] = [
                'user_id'=>$u['id'],
                'tips_id'=>$id,
                'title'=>$tip['title'],
                'cate'=>$tip['cate'],
                'msg'=>$tip['msg'],
                'create_time'=>time(),
                'update_time'=>time(),
                'is_read'=>0
            ];
            if($u['register_id']){
                $push_data[] = $u['register_id'];
            }
        }
        //准备推送
        Log::error($push_data);
        pushMess($tip['msg'], ['tips_id'=>$id], ['registration_id'=>$push_data], $tip['title']);
        $res = db("user_tips")->insertAll($insert_data);
        if($res){
            $tip->save(["nums"=>$tip['nums']+count($insert_data), "send_time"=>date('y-m-d H:i')]);
            exit_json();
        }else{
            exit_json(-1, "推送失败");
        }
    }

    /**
     * 新闻
     */
    public function news()
    {
        //TODO
        
    }

    /**
     * 新闻添加
     */
    public function newsAdd()
    {
        //TODO


    }

    /**
     * 新闻分类
     */
    public function newsCate()
    {
        //TODO
    }

    /**
     * 添加新闻分类
     */
    public function newsCateAdd()
    {
        //TODO
    }



}