<?php
/**
 * 角色管理
 * User: Administrator
 * Date: 2018/2/12
 * Time: 15:17
 */

namespace app\admin\model;

use think\Model;

class Role extends Model
{
    protected $autoWriteTimestamp = true;

    public function initialize()
    {
        parent::initialize();
    }

    protected function getCreateTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * 根据角色id获取节点列表
     * @param $roleId
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    function getNodeList($roleId)
    {
        $nodeList = $this->where(array('id' => ['in', $roleId]))->column("node_id");
        $node_arr = explode(",", implode(",", $nodeList));
        if(in_array("all", $node_arr)){
            return "all";
        }
//        return $nodeList['node_id'];
        return implode(",", $node_arr);
    }

    /**
     * 获取角色名称
     * @param $roleId
     * @return mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    function getRoleName($roleId)
    {
        if ($roleId != 0) {
            $role = $this->where(['id' => ['in', $roleId]])->column("role_name");
            return implode(",", $role);
        } else {
            return '超级管理员';
        }
    }


}