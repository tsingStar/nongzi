<?php
/**
 * Created by PhpStorm.
 * User: tsing
 * Date: 2019/1/18
 * Time: 18:02
 */

namespace app\agent\controller;


use app\admin\model\Admins;
use think\Controller;

class BaseAgent extends Controller
{
    protected $agent;
    protected function _initialize()
    {
        parent::_initialize();
        if(!session("agent_id")){
            $this->redirect("Pub/login");
        }else{
            $this->agent = Admins::get(session("agent_id"));
        }
    }

}