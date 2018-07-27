<?php
/**
 * 平台商品分类类
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15
 * Time: 14:17
 */

namespace app\common\model;


use think\Model;

class ProductCate extends Model
{
    protected $autoWriteTimestamp = true;

    public function __construct($data = [])
    {
        parent::__construct($data);
    }

    /**
     * 获取商品分类
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCategory($parent_id = 0)
    {
        $list = $this->where(['parent_id' => $parent_id])->select();
        return $list;
    }

    /**
     * 删除指定分类
     * @param $cid
     * @return int
     */
    function delCate($cid)
    {
        $cid = explode(',', $cid);
        $ids = $this->where('parent_id', 'in', $cid)->column('id');
        $ids = array_merge($cid, $ids);
        return $this->where('id', 'in', $ids)->delete();
    }


}