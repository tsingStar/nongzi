<?php
/**
 * Created by PhpStorm.
 * User: tsing
 * Date: 2019/4/28
 * Time: 21:47
 */

namespace app\admin\controller;


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

}